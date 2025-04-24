<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Models\User;
use App\Session\Session;
use App\Validators\Auth\AuthValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use Exception;
use ReallySimpleJWT\Token;


class AuthController extends Controller
{

    protected Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }
    public function index()
    {
        View::page('login');

        return $this->response(Status::OK, ['message' => 'Login page loaded']);
    }

    public function auth()
    {
        $fields = requestBody();


        if (AuthValidator::validate($fields))
        {
            $user = User::findBy('email', $fields['email']);

            if (password_verify($fields['password'], $user->password)) {

                $expired_at = time() + 3600;

                $token = Token::create($user->id, $user->password, $expired_at, 'localhost');

                $user->update([
                    'token' => $token,
                    'token_expired_at' => $expired_at
                ]);

                setcookie("token", $token, time() + 3600, "/", "", false, true);
                Redirect::to('/');
                exit;
            }
        }
        $this->session->set('errors', AuthValidator::getErrors());

        Redirect::to('/auth');
        exit;


    }

    public function isAuth(): bool
    {
        if (!empty($_COOKIE['token'])) {
            $token = $_COOKIE['token'];

            try {
                $payload = Token::getPayload($token);

                if (!isset($payload['exp']) || !isset($payload['user_id'])) {
                    return false;
                }

                if ($payload['exp'] < time()) {
                    return false;
                }

                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function authEmail() : string
    {
        if (isset($_COOKIE['token'])) { // Проверяем, установлено ли куки
            $user_token = $_COOKIE['token'];
            $user = User::findBy('token', $user_token); // Ищем пользователя по токену
            if ($user) { // Проверяем, найден ли пользователь
                return $user->email;
            }
        }
        return 'user not found';
    }

    public function logout(): void
    {
        setcookie("token", "", time() - 3600, "/", "", false, true);

        if (!empty($_COOKIE['token'])) {
            $user = User::findBy('token', $_COOKIE['token']);
            if ($user) {
                $user->update([
                    'token' => null,
                    'token_expired_at' => null
                ]);
            }
        }

        Redirect::to("/");
        exit();
    }

    public function authId(): ?int
    {
        if (!$this->isAuth()) {
            return null;
        }

        $token = $_COOKIE['token'] ?? null;
        if (!$token) return null;

        $payload = Token::getPayload($token);
        return $payload['user_id'] ?? null;
    }

}