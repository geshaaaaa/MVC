<?php

namespace App\Commands\Contract;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

interface Command
{
    public function __construct(CLI $cli, Options $options,array $args);
    public function handle() : void;

}