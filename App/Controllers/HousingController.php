<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Housing;
use App\Models\Reservations;
use App\Session\Session;
use App\Validators\HousingValidation\HousingValidator;
use App\Validators\ReservationValidation\ReservationValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Controllers\ReservationController;

class HousingController extends Controller
{
    private Session $session;
    private ReservationController $reservationController;

    public function __construct()
    {
        $this->session = new Session();
        $this->reservationController = new ReservationController();
    }

    public function index()
    {
        View::page('search');

        return $this->response(Status::OK, ['message' => 'Search page loaded']);
    }

    public function searchHousing()
    {
        $fields = $_GET;

        $location = $fields['location'];
        (int)$guests = $fields['guests_capacity'];
        $checkIn = $fields['check_in'] ? date('Y-m-d', strtotime($fields['check_in'])) : null;
        $checkOut = $fields['check_out'] ? date('Y-m-d', strtotime($fields['check_out'])) : null;

        $reservedHousingIds =  $this->reservationController->getReservedHousingIds($checkIn,$checkOut);

            if (HousingValidator::validate(['location' => $location, 'guests_capacity' => $guests])) {

                $query = Housing::select()
                    ->where('location', CommandsSQL::EQUAL, $location)
                    ->and('guests_capacity_max', CommandsSQL::GREATER_EQUAL, $guests);

                if (!empty($reservedHousingIds)) {
                    $query->whereNotIn('id', $reservedHousingIds);
                }

                $results = $query->get();


                $this->session->set('search_results', $results);

                Redirect::to("/search");
                exit;
            }


        $this->session->set('errors', HousingValidator::getErrors());
        Redirect::to("/");
        exit;

    }

}