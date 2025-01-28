<?php

namespace App\Commands;

use App\Commands\Contract\Command;
use Core\DB;
use seeders\Seeders;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use PDOException;
use Exception;

class Seed implements Command
{

    public function __construct(public CLI $cli,Options $options,public array $args)
    {
    }

    public function handle(): void
    {
        try {
            DB::connect()->beginTransaction();
            $this->cli->info("Seed process has been start...");

            $this->runSeeds();

            DB::connect()->commit();
            $this->cli->success("Seed process has been done!");
        } catch (PDOException $exception) {
            if (DB::connect()->inTransaction()) {
                DB::connect()->rollBack();
            }
            $this->cli->fatal($exception->getMessage());
        } catch (Exception $exception) {
            $this->cli->fatal($exception->getMessage());
        }
    }

    protected function runSeeds(): void
    {
        if (!empty(Seeders::$seeds)) {
            foreach(Seeders::$seeds as $seedClass) {
                /**
                 * @var Seeders $seed
                 */
                $seed = new $seedClass;
                $seed->run();
            }
        }
    }
}