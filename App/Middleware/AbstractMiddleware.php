<?php

namespace App\Middleware;

use App\Controllers\AuthController;
use Core\Redirect\Redirect;

abstract class AbstractMiddleware
{
    public function __construct(protected AuthController $auth, protected Redirect $redirect)
    {}
    abstract public function handle(): void;

}