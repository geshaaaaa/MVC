<?php

use App\Controllers\AuthController;
use App\Controllers\FoldersController;
use Core\Router;


Router::post('api/register')
    ->controller(AuthController::class)
    ->actions('register');



Router::post('api/auth')
    ->controller(AuthController::class)
    ->actions('auth');

Router::get('api/folders')
    ->controller(FoldersController::class)
    ->actions('index');

Router::get('api/folders/{id:\d+}')
    ->controller(FoldersController::class)
    ->actions('show');

Router::post('api/folders/store')
    ->controller(FoldersController::class)
    ->actions('store');

Router::put('api/folders/{id:\d+}/update')
    ->controller(FoldersController::class)
    ->actions('update');

Router::delete('api/folders/{id:\d+}/destroy')
    ->controller(FoldersController::class)
    ->actions('destroy');
