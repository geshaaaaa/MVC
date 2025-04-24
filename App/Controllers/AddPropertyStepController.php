<?php

namespace App\Controllers;

use App\Models\Housing;
use App\Models\User;
use App\Validators\HousingValidation\HousingValidator;
use App\Validators\OwnerValidation\OwnerValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Session\Session;
use App\Controllers\AuthController;

class AddPropertyStepController extends Controller
{
    private Session $session;
    private AuthController $isLogged;
    public function __construct()
    {
        $this->session = new Session();

        $this->isLogged = new AuthController();
    }




    public function index()
    {
        if (!$this->isLogged->isAuth()) {
            echo "<script>alert('Будь ласка, увійдіть до системи, щоб розмістити оголошення.'); window.location.href = '/auth';</script>";
            exit();
        }

        $userId = $this->isLogged->authId();
        $user = User::find($userId);

        if ($user->role !== 'hotel_staff' && $user->role !== 'admin') {
            echo "<script>alert('У вас немає дозволу на розміщення помешкання.'); window.location.href = '/';</script>";
            exit();
        }

        View::page('add_property');
        exit();
    }

    public function store()
    {
        if (!$this->isLogged->isAuth()) {
            // Выводим предупреждение и прерываем выполнение
            echo "<script>alert('Будь ласка, увійдіть до системи, щоб розмістити оголошення.'); window.location.href = '/auth';</script>";
            exit();
        }

        $fields = requestBody();

        $this->session->set('housing_data', $fields);
        Redirect::to('/add-property-3');
        exit();

    }




}