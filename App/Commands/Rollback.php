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

class Rollback implements Command
{
    const string MIGRATIONS_DIR = BASE_DIR . '/Migrations';

    public function __construct(public CLI $cli,public Options $options ,public array $args = [])
    {

    }

    public function handle(): void
    {
        try {
            DB::connect()->beginTransaction();
            $this->cli->info("Rollback process has been started...");

            $this->rollbackMigrations();
            $this->deleteLastMigrationsRecords();

            DB::connect()->commit();
            $this->cli->info("Rollback process has been finished...");
        } catch (Throwable $e) {
            if (DB::connect()->inTransaction()) {
                DB::connect()->rollBack();
            }
            $this->cli->fatal($e->getMessage());
        }
    }

    protected function rollbackMigrations(): void
    {
        $this->cli->info("");
        $this->cli->info("Rollback migrations...");

        $migrations = $this->getLastMigrations();

        if (empty($migrations)) {
            $this->cli->info('Nothing to rollback');
            exit;
        }

        foreach ($migrations as $fileName) {
            $name = preg_replace('/[\d]+_/', '', $fileName);
            $this->cli->notice("- rollback $name");

            $script = $this->getScript($fileName); # get script

            if (empty($script)) {
                $this->cli->warning("An empty script!");
                continue;
            }

            $query = DB::connect()->prepare($script);

            if ($query->execute()) {
                $this->cli->success("- $name was successfully rollbacked!");
            }
        }
    }

    protected function getScript(string $fileName): string
    {
        $obj = null;
        $obj = require_once self::MIGRATIONS_DIR . '/' . $fileName;
        return $obj?->down() ?? '';
    }

    protected function getLastMigrations($column = 'name'): array
    {
        $query = DB::connect()->prepare("SELECT $column FROM migrations WHERE batch IN (
            SELECT MAX(batch) as batch FROM migrations
        ) ORDER BY id DESC");
        $query->execute();

        return array_map(fn ($item) => $item[$column], $query->fetchAll(PDO::FETCH_ASSOC));
    }

    protected function deleteLastMigrationsRecords(): void
    {
        $migrations = implode(', ', $this->getLastMigrations('id'));
        $query = DB::connect()->prepare("DELETE FROM migrations WHERE id IN ($migrations)");
        $query->execute();
    }
}