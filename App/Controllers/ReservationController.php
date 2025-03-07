<?php

namespace App\Controllers;

use App\Enums\SQL\CommandsSQL;
use App\Models\Reservations;
use App\Validators\ReservationValidation\ReservationValidator;
use Core\Controller;
use App\Session\Session;
use Core\Redirect\Redirect;

class ReservationController extends Controller
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public  function getReservedHousingIds($checkIn, $checkOut): array
    {
        if (!ReservationValidator::validate(['check_in' => $checkIn, 'check_out' => $checkOut])) {
            $this->session->set('errors', ReservationValidator::getErrors());
            Redirect::to("/");
            exit;
        }

        return Reservations::select(['housing_id'])
            ->where('check_in', CommandsSQL::LESS, $checkOut)
            ->and('check_out', CommandsSQL::GREATER, $checkIn)
            ->pluck('housing_id');
    }


}