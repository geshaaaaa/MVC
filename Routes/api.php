<?php

use App\Controllers\AuthController;
use Core\Router;

Router::get('users')
        ->controller(AuthController::class)
        ->actions('register');





