<?php

namespace App\Middleware;

class AuthMiddleware extends AbstractMiddleware
{

    #[\Override] public function handle(): void
    {
        if (!isset($_COOKIE['token']))
        {
            $this->redirect::to("/auth");
            exit;
        }
    }
}