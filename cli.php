<?php

const BASE_DIR = __DIR__;
require_once BASE_DIR . "/vendor/autoload.php";

use App\Commands\Contract\Command;
use splitbrain\phpcli\CLI;
use Dotenv\Dotenv;
use splitbrain\phpcli\Options;
use App\Commands\Create;
use App\Commands\Run;
use Core\DB;


Dotenv::createUnsafeImmutable(BASE_DIR)->load();

class SmallCLI extends CLI
{
    private array $commands = [];
    public function __construct()
    {
        parent::__construct();

        $this->commands = [
            "create" => Create::class,
            "run" =>  Run::class
        ];
    }

    #[\Override] protected function setup(Options $options)
    {
        $options->setHelp("Створення міграцій та їх застосування");
        $options->registerCommand("create", "Створити нову міграцію");
        $options->registerCommand("run", "Запустити міграцію");
        $options->registerArgument("name", "Назва міграції, тільки для create", true);

    }
    #[\Override] protected function main(Options $options)
    {
        $commandName = $options->getCmd();
        $args = $options->getArgs();

        if (!isset($this->commands[$commandName])) {
            $this->error("Даної команди не знайдено в існуючих командах");
        }
        try {
            $commandClass = $this->commands[$commandName];

            if (is_subclass_of($commandClass,Command::class))
            {
                $command = new $commandClass($this, $options, $args);
                $command->handle();
            }
            else {
                $this->error("Команда, ще не реалізована");
            }

        }catch (Throwable $e)
        {
            $this->error("Помилка виконання команди: " . $e->getMessage());
        }
        }

}


$smallCli = new SmallCLI();
$smallCli->run() . PHP_EOL;
