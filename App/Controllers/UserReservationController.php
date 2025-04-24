<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Reservations;
use App\Models\User;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use DateTime;
use splitbrain\phpcli\Exception;

class UserReservationController extends Controller
{
    public function index()
    {
        $userId = $this->reservationUserId();

        if ($userId === null) {
            View::page('user_reservation');
            exit;
        }

        $reservations = $this->getUserReservations($userId);

        View::page('user_reservation', $reservations);
        exit;
    }

    public function reservationUserId() : int|null
    {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $user = User::findBy('token', $token);
            if ($user)
            {
                return $user->id;
            }
        }
        return null;
    }

   private function getUserReservations($userId): array
    {

        $reservations = Reservations::where('users_id', CommandsSQL::EQUAL, $userId)->get();

        $futureReservations = [];
        $pastReservations = [];

        $now = new DateTime();



        foreach ($reservations as $reservation) {
            if ($reservation->check_out) {
                $checkOutDate = new DateTime($reservation->check_out);
                if ($checkOutDate > $now) {
                    $futureReservations[] = $reservation;
                } else {
                    $pastReservations[] = $reservation;
                }
            } else {
                $pastReservations[] = $reservation;
            }
        }

        return [
            'futureReservations' => $futureReservations,
            'pastReservations' => $pastReservations,
        ];
    }

    public function cancelReservation()
    {

        // Получаем ID пользователя
        $userId = $this->reservationUserId();

        $reservationId = $_GET['id'];

        // Получаем бронирование по ID
        $reservation = Reservations::find($reservationId);

        if (!$reservation) {
            return $this->response(Status::NOT_FOUND, ['error' => 'Бронювання не знайдено.']);
        }

        // Проверяем, что бронирование принадлежит текущему пользователю
        if ($reservation->users_id !== $userId) {
            return $this->response(Status::FORBIDDEN, ['error' => 'Ви не маєте прав на відміну цього бронювання.']);
        }

        // Удаляем бронирование
        Reservations::delete($reservationId);

        Redirect::to('user_reservation');
        exit('Бронювання видалено');
    }


}