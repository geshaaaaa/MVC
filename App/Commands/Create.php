<?php

namespace App\Commands;



use App\Commands\Contract\Command;
use splitbrain\phpcli\Exception;
use splitbrain\phpcli\Options;
use splitbrain\phpcli\CLI;
use App\Commands\Contract\MigrationSample;
use Throwable;

class Create implements Command
{
   const string MIGRATION_PATH = BASE_DIR . "/Migrations";

    private array $args = [];
    public function __construct(protected CLI $cli, protected Options $options, array $args)
    {
        $this->args = $args;
    }
    #[\Override] public function handle(): void
    {
        $this->createDir();


        $this->createMigration();
    }


    private function createMigration() : void
    {
        $name = $this->args[0] ?? null;
        if ($name == null)
        {
            throw new Exception("Не задано ім'я міграції");
        }

        $time = date("Ymd_His");

        $filename = "migration_{$time}_{$name}.php";

        $filepath = self::MIGRATION_PATH . "/{$filename}";
        file_put_contents($filepath,  MigrationSample::TEMPLATE, FILE_APPEND);

        $this->cli->info("Міграция створена: {$filepath}");
    }
    private function createDir() : void
    {
        if (!is_dir(self::MIGRATION_PATH))
        {
            mkdir(self::MIGRATION_PATH,recursive: true);
        }
        $this->cli->info("Директорія створена");
    }
}