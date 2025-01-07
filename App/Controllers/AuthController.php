<?php

namespace App\Controllers;

use App\Enums\SQL\CommandsSQL;
use App\Models\User;
use Core\Controller;
class AuthController extends Controller
{
    public function register() : void
    {
        dd(User::create([
            "email" => "geshakurkotov@gmail.com",
            "password" => "123",
            "userType" => "Agency"
        ]));

    }

}