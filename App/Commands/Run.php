<?php

namespace App\Commands;

use App\Commands\Contract\Command;
use Core\DB;
use PDO;
use PDOException;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Exception;
use splitbrain\phpcli\Options;
use Throwable;

class Run implements Command
{
    const string MIGRATIONS_DIR = BASE_DIR . '/Migrations';

    public function __construct(public CLI $cli, Options $options,public array $args = [])
    {
    }

    public function handle(): void
    {

        try {
            DB::connect()->beginTransaction();
            $this->cli->info("Migration process has been started...");

            $this->createMigrationsTable();

            $this->runMigrations();

            DB::connect()->commit();
            $this->cli->info("Migration process has been finished...");
        } catch (Throwable $e) {
            if (DB::connect()->inTransaction()) {
                DB::connect()->rollBack();
            }
            $this->cli->fatal($e->getMessage());
        }
    }

    protected function runMigrations(): void
    {
        $this->cli->info("");
        $this->cli->info("Run migrations...");

        $migrations = $this->prepareMigrationsList();

        if (empty($migrations['list'])) {
            $this->cli->info('Nothing to migrate');
            exit;
        }

        $migrations['batch']++;

        foreach ($migrations['list'] as $fileName) {
            $name = preg_replace('/[\d]+_/', '', $fileName);
            $this->cli->notice("- run $name");

            $script = $this->getScript($fileName); # get script

            if (empty($script)) {
                $this->cli->warning("An empty script!");
                continue;
            }

            $query = DB::connect()->prepare($script);

            if ($query->execute()) {
                $this->createMigrationRecord($fileName, $migrations['batch']);
                $this->cli->success("- $name migrated!");
            }
        }
    }

    protected function createMigrationRecord(string $fileName, int $batch): void
    {
        $query = DB::connect()->prepare("INSERT INTO migrations (name, batch) VALUES (:name, :batch)");
        $query->bindParam('name', $fileName);
        $query->bindParam('batch', $batch, PDO::PARAM_INT);
        $query->execute();
    }

    protected function getScript(string $fileName): string
    {
        $obj = null;
        $obj = require_once self::MIGRATIONS_DIR . '/' . $fileName;
        return $obj?->up() ?? '';
    }

    protected function prepareMigrationsList(): array
    {
        $this->cli->info("- fetch migrations...");
        $migrations = scandir(self::MIGRATIONS_DIR);
        $migrations = array_values(array_diff($migrations, ['.', '..']));

        $handledMigrations = $this->handledMigrationsList();

        return [
            'list' => array_values(array_diff($migrations, $handledMigrations['list'])),
            'batch' => $handledMigrations['batch'],
        ];
    }

    protected function handledMigrationsList(): array
    {
        $query = DB::connect()->prepare("SELECT name, batch FROM migrations");
        $query->execute();

        return array_reduce(
            $query->fetchAll(PDO::FETCH_ASSOC),
            function (array $result, array $item): array {
                $result['list'][] = $item['name'];

                if ($item['batch'] > $result['batch']) {
                    $result['batch'] = $item['batch'];
                }

                return $result;
            },
            [
                'list' => [],
                'batch' => 0
            ]
        );
    }

    protected function createMigrationsTable(): void
    {
        $this->cli->info("- run 'migrations' table script...");

        $query = DB::connect()->prepare("
            CREATE TABLE IF NOT EXISTS migrations (
                id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
                name VARCHAR(255) NOT NULL UNIQUE,
                batch SMALLINT UNSIGNED NOT NULL
            )
        ");

        if (!$query->execute()) {
            throw new Exception("- something went wrong with 'migrations' table script");
        }

        $this->cli->info("- 'migrations' table was checked/created");
    }
}