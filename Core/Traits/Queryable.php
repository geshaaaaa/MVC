<?php

namespace Core\Traits;
use App\Enums\SQL\CommandsSQL;
use Core\DB;
use splitbrain\phpcli\Exception;
use PDO;


trait Queryable
{
    protected array $commands = [];

    protected static ?string $tableName = null;

    static protected string $query = '';

    static public function __callStatic(string $name, array $arguments)
    {
        if (in_array($name, ['where', 'join']))
        {
            return call_user_func_array([new static, $name],$arguments);
        }

        throw new Exception('Method not allowed', 422);
    }

    public function __call(string $name, array $arguments)
    {
        if (in_array($name, ['where', 'join']))
        {
            return call_user_func_array([$this, $name], $arguments);
        }
        throw new Exception('Method not allowed', 422);
    }

    static protected function resetQuery() : void
    {
        static::$query = '';

    }

    static public function select(array $columns = ["*"]): static
    {
        static::resetQuery();

        static::$query .= "SELECT" . implode(",", $columns). "FROM " . static::$tableName;

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }


    static public function delete(int $id) : bool
    {


        $query = DB::connect()->prepare("DELETE FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam("id", $id, PDO::PARAM_INT);

        return $query->execute();
    }

    public function get() : array
    {
        return DB::connect()->query(static::$query)->fetchAll(PDO::FETCH_CLASS,static::class);
    }
    static public function all(): array
    {
        return static::select()->get();
    }

    public function toSQL() : string
    {
        return static::$query;
    }

    protected function where(string $column,CommandsSQL $operator = CommandsSQL::EQUAL ,mixed $value = null) : static
    {
        $this->prevent(['order', 'limit', 'having', 'group'], 'WHERE CANT BE USED AFTER THESE COMMANDS');

        $obj = in_array("select", $this->commands) ? $this : static::select();

        if (!in_array("where", $obj->commands))
        {
            static::$query .= " WHERE";
            $obj->commands[] = "where";
        }
        static::$query = "$column $operator->value $value";

        return $obj;
    }


    protected function prevent(array $preventCommands, string $message = ''): void
    {
        foreach ($preventCommands as $command) {
            if (in_array($command, $this->commands)) {
                $message = sprintf(
                    '%s: %s [%s]',
                    static::class,
                    $message,
                    $command
                );
                throw new Exception($message, 422);
            }
        }
    }

    static public function create(array $fields) : bool
    {
        $params = static::prepareCreate($fields);
        $query = DB::connect()->prepare("INSERT INTO " . static::$tableName . " ($params[keys]) VALUES ($params[placeholders]);");
        return $query->execute($fields);
    }


    static protected function prepareCreate(array $fields) : array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/',':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders)
        ];
    }
    public function find(int $id) : static|false
    {
        $query = DB::connect()->prepare("SELECT * FROM" . static::$tableName . "WHERE id = :id" );
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public function findBy(int $column, mixed $value) : static|false
    {
        $query = DB::connect()->prepare("SELECT * FROM" . static::$tableName . "WHERE $column = :$column" );
        $query->bindParam($column, $value, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(static::class);
    }

     public function update(array $fields) : static
    {
        $query = DB::connect()->prepare("UPDATE " . static::$tableName . " SET " . $this->updatePlaceholders($fields) . ' WHERE id = :id');

        $fields['id'] = $this->id;
        $query->execute($fields);

        return static::find($this->id);

    }


    protected function updatePlaceholders(array $fields): string
    {
        $keys = array_map(fn ($key) => "$key = :$key", array_keys($fields));

        return implode(', ', $keys);
    }

}