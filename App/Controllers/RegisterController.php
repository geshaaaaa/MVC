<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Models\User;
use App\Session\Session;
use App\Validators\Auth\RegisterValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;

class RegisterController extends Controller
{
    protected Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function index()
    {
        View::page('register');

        return $this->response(Status::OK, ['message' => 'Register page loaded']);
    }

    public function register()
    {
        $fields = requestBody();

        if (RegisterValidator::validate($fields))
        {
            $user = User::createAndReturn([
                ...$fields,
                'password' => password_hash($fields['password'], PASSWORD_ARGON2ID),
            ]);
            Redirect::to('/auth');
            exit;
        }

        $this->session->set('errors', RegisterValidator::getErrors() );
        Redirect::to('/register');
        exit;
    }



}