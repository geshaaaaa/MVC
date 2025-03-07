<?php

namespace App\Middleware;

class AuthMiddleware extends AbstractMiddleware
{

    #[\Override] public function handle(): void
    {
        if (!$this->auth->isAuth())
        {
            $this->redirect::to("/auth");
            exit;
        }
    }
}