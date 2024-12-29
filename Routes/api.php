<?php

use App\Controllers\AuthController;
use Core\Router;

Router::get('admin/users/{id:\d+}/posts/{post_id:\d+}')
        ->controller(AuthController::class)
        ->actions('register');





