<?php

use App\Controllers\AuthController;
use App\Controllers\FoldersController;
use App\Controllers\HousingController;
use App\Controllers\NotesController;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;
use App\Controllers\WishlistsController;
use Core\Router;
use App\Middleware\AuthMiddleware;

Router::get('')
    ->controller(HomeController::class)
    ->actions('index')
    ->middleware([AuthMiddleware::class]);


Router::post('api/register')
    ->controller(RegisterController::class)
    ->actions('register');

Router::get('register')
    ->controller(RegisterController::class)
    ->actions('index');

Router::get('auth')
    ->controller(AuthController::class)
    ->actions('index');

Router::post('api/auth')
    ->controller(AuthController::class)
    ->actions('auth');

Router::get('logout')
    ->controller(AuthController::class)
    ->actions('logout');

Router::get('search')
    ->controller(HousingController::class)
    ->actions('index');

Router::get('api/search')
    ->controller(HousingController::class)
    ->actions('searchHousing');

Router::post('toggle-wishlist')
    ->controller(WishlistsController::class)
    ->actions('toggleWishlist');

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

Router::get('api/notes')
    ->controller(NotesController::class)
    ->actions('index');

Router::get('api/notes/{id:\d+}')
    ->controller(NotesController::class)
    ->actions('show');

Router::post('api/notes/store')
    ->controller(NotesController::class)
    ->actions('store');

Router::put('api/notes/{id:\d+}/update')
    ->controller(NotesController::class)
    ->actions('update');

Router::delete('api/notes/{id:\d+}/destroy')
    ->controller(NotesController::class)
    ->actions('destroy');