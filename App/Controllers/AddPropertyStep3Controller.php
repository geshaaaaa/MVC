<?php

namespace App\Controllers;

use App\Session\Session;
use App\Validators\HousingValidation\HousingValidator;
use App\Validators\OwnerValidation\OwnerValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;

class AddPropertyStep3Controller extends Controller
{
    private Session $session;
    public function __construct()
    {
        $this->session = new Session();

    }

    public function index()
    {
        View::page('/add-property-3');
        exit();
    }

    public function store()
    {
        $fields = requestBody();

        $guests = $fields['guests_capacity_max'];
        $errors = [];



        // Валідація на порожні поля
        $requiredFields = ['location', 'guests_capacity_max', 'title', 'description'];
        foreach ($requiredFields as $field) {
            if (empty($fields[$field])) {
                $errors[$field] = match ($field) {
                    'location' => 'Місцезнаходження не може бути порожнім.',
                    'guests_capacity' => 'Кількість гостей не може бути порожньою.',
                    'title' => 'Назва об\'єкта не може бути порожньою.',
                    'description' => 'Опис об\'єкта не може бути порожнім.',
                    default => 'Поле не може бути порожнім.',
                };
            }
        }

        if (!empty($errors)) {
            $this->session->set('errors', $errors);
            Redirect::to("/add-property-3");
            exit;
        }

        if (HousingValidator::validate(['location'=> $fields['location'],'guests_capacity' => $guests])) {
            $this->session->set('housing_data', $fields);

             Redirect::to('/add-price-images');
            exit;
        }


        $errors = array_merge($errors, HousingValidator::getErrors());
        $this->session->set('errors', $errors);
        Redirect::to("/add-property-3");
        exit;
    }


}