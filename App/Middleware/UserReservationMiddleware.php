<?php

namespace App\Middleware;

class UserReservationMiddleware extends AbstractMiddleware
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token'])) {
            $_SESSION['auth_error'] = 'Для доступа к бронированиям необходимо войти в систему.';
            header('Location: /user_reservation');
            exit;
        }
    }

}