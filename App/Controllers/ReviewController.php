<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Review;
use App\Models\User;
use Core\Controller;

class ReviewController extends Controller
{

    public function submitRating()
    {
        $fields = requestBody();

        $userId = $this->reviewUserId();
        if (!$userId) {
            return $this->response(Status::UNAUTHORIZED, ['error' => 'Необхідно увійти в систему']);
        }

        if (!isset($fields['housingId']) || !isset($fields['rating'])) {
            return $this->response(Status::BAD_REQUEST, ['error' => 'Некоректний запит']);
        }

        $housingId = (int) $fields['housingId'];
        $rating = (int) $fields['rating'];

        // Проверяем, есть ли уже оценка
        $existingReview = Review::where('user_id', CommandsSQL::EQUAL, $userId)
            ->and('housing_id', CommandsSQL::EQUAL, $housingId)
            ->first();

        if ($existingReview) {
            // Если уже есть оценка, удаляем её
            Review::delete($existingReview->id);
        }

        // Записываем новую оценку
        Review::create([
            'user_id' => $userId,
            'housing_id' => $housingId,
            'rating' => $rating
        ]);

        return $this->response(Status::OK, ['message' => 'Оцінку додано/оновлено']);
    }

    public function reviewUserId(): array|int
    {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $user = User::findBy('token', $token);
            if ($user) {
                return $user->id;
            }
        }
        return $this->response(Status::UNAUTHORIZED, ['error' => 'Необходимо войти в систему']);
    }


    public function avgRating()
    {
        $fields = requestBody();

        if (!isset($fields["housingId"])) {
            return $this->response(Status::BAD_REQUEST, ['error' => 'Некоректний запит']);
        }

        $housingId = (int) $fields['housingId'];

        $ratings = Review::where("housing_id", CommandsSQL::EQUAL, $housingId);

        if ($ratings->isEmpty()) { // Теперь используем новый метод isEmpty()
            return $this->response(Status::OK, ['avg_rating' => 0]);
        }

        $totalRating = 0;
        $count = 0;



        foreach ($ratings->get() as $review) {
            if ($review->rating !== null) {
                $totalRating += $review->rating;
                $count++;
            }
        }

        if ($count > 0) {
            $avgRating = $totalRating / $count;
            return $this->response(Status::OK, ['avg_rating' => round($avgRating, 1)]);
        } else {
            return $this->response(Status::OK, ['avg_rating' => 0]);
        }
    }


}