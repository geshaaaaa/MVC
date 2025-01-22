<?php

use App\Controllers\AuthController;
use Core\Router;


Router::post('api/register')
    ->controller(AuthController::class)
    ->actions('register');



Router::post('api/auth')
    ->controller(AuthController::class)
    ->actions('auth');

