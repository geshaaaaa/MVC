<?php

namespace App\Controllers;

use App\Controllers\AuthController;
use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\User;
use App\Models\Wishlists;
use App\Session\Session;
use Core\Controller;


class WishlistsController extends Controller
{


    public function toggleWishlist()
    {
        $fields = requestBody();

        $auth = new AuthController();

        $userId = $this->wishlistUserId();


        if (!$auth->isAuth()) {
            return $this->response(Status::UNAUTHORIZED, ['error' => 'Необхідно увійти в систему']);
        }


        if (!isset($fields['housing_id'])) {
            return $this->response(Status::BAD_REQUEST, ['error' => 'Некоректний запит']);
        }

        $housingId = $fields['housing_id'];


        $id = Wishlists::where('user_id', CommandsSQL::EQUAL, $userId)
            ->and('housing_id', CommandsSQL::EQUAL,$housingId)
            ->pluck('id')[0] ?? null;

        if ($id) {
            Wishlists::delete($id);

            return $this->response(Status::OK, ['message' => 'Видалено з списка бажань']);
        }

        else {

            Wishlists::create([
                'user_id' => $userId,
                'housing_id' => $housingId
            ]);
            return $this->response(Status::OK, ['message' => 'Додано у список бажань']);
        }

    }

    public function wishlistUserId() : array|int
    {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $user = User::findBy('token', $token);
            if ($user)
            {
                return $user_id = $user->id;
            }
        }
        return $this->response(Status::UNAUTHORIZED, ['error' => 'Необходимо войти в систему']);
    }



}