<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Housing;
use App\Models\Reservations;
use App\Models\User;
use App\Validators\ReservationValidation\ReservationValidator;
use Core\Controller;
use App\Session\Session;
use Core\Redirect\Redirect;
use Core\View\View;

class ReservationController extends Controller
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function index()
    {
        View::page('reservation');

        return $this->response(Status::OK,  ['message' => 'Reservation loaded']);

    }

    public  function getReservedHousingIds($checkIn, $checkOut): array
    {
        if (!isset($checkIn))
        {
            $this->session->set('errors', ReservationValidator::getDateErrors());
            Redirect::to("/");
            exit;
        }
        if (!isset($checkOut))
        {
            $this->session->set('errors', ReservationValidator::getDateErrors());
            Redirect::to("/");
            exit;
        }

        if (!ReservationValidator::validateDates($checkIn, $checkOut)) {
            $this->session->set('errors', ReservationValidator::getDateErrors());
            Redirect::to("/");
            exit;
        }

        return Reservations::select(['housing_id'])
            ->where('check_in', CommandsSQL::LESS, $checkOut)
            ->and('check_out', CommandsSQL::GREATER, $checkIn)
            ->pluck('housing_id');
    }

    public function reservationForm()
    {
        $fields = requestBody();

        $userId = $this->reservationUserId();

        $fields['user_id'] = $userId;
        $fields['status'] = 'pending';

        $fields['check_in'] = $fields['check_in'] ? date('Y-m-d', strtotime($fields['check_in'])) : null;
        $fields['check_out']  = $fields['check_out'] ? date('Y-m-d', strtotime($fields['check_out'])) : null;


        if (!ReservationValidator::validate($fields)) {

            $this->session->set('errors', ReservationValidator::getErrors());
            Redirect::to("/reservation");
            exit;
        }



        $reservation = Reservations::create([
            'users_id' => $userId,
            'housing_id' => $fields['housing_id'],
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'phone_number' => $fields['phone_number'],
            'check_in' => $fields['check_in'],
            'check_out' =>  $fields['check_out'],
            'total_price' => $fields['total_price'],
            'notes' => $fields['notes'],
            'status' => 'confirmed'
        ]);


        $this->session->remove('reservation_data');

        if ($reservation) {
            $this->session->set('success', 'Бронювання успішно!');
            $this->session->remove('reservation_data');
            Redirect::to('/user_reservation');
        } else {
            $this->session->set('errors', 'Помилка бронювання.');
            Redirect::to('/reservation');
        }
        exit;
    }

    public function saveReservationData()
    {
        $fields = requestBody();



        if (!isset($fields['check_in'], $fields['check_out'], $fields['total_price'], $fields['housing_id'])) {
            return $this->response(Status::BAD_REQUEST, ["error" => "Не всі данні передані."]);
        }



        if (!ReservationValidator::validateDates($fields['check_in'], $fields['check_out']))
        {
            $this->session->set('errors', ReservationValidator::getDateErrors());
            Redirect::to("/details?id=" . $fields['housing_id']);
            exit;
        }



        $housing = Housing::findBy('id',$fields['housing_id']);

        if (!$housing) {
            return $this->response(Status::NOT_FOUND, ["error" => "Житло не знайдено."]);
        }

        // Добавляем в данные title и location
        $fields['title'] = $housing->title;
        $fields['location'] = $housing->location;

        // Сохраняем данные, если даты корректны
        $this->session->set("reservation_data", $fields);

        Redirect::to("/reservation");
        exit;
    }

    public function reservationUserId() : array|int
    {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $user = User::findBy('token', $token);
            if ($user)
            {
                return $user->id;
            }
        }
        return $this->response(Status::UNAUTHORIZED, ['error' => 'Необходимо войти в систему']);
    }



}