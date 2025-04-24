<?php

namespace App\Validators\ReservationValidation;

use App\Validators\BaseValidator;
use DateTime;
use Exception;

class Base extends BaseValidator

{



    protected static array $rules = [

        'housing_id' => '/^\d+$/',

        'user_id' => '/^\d+$/',

        'status' => '/^(pending|confirmed|cancelled)$/',

        'first_name' => '/^[a-zA-Zа-яА-ЯїєґІй\s\-]+$/u',

        'last_name' => '/^[a-zA-Zа-яА-ЯїєґІй\s\-]+$/u',

        'phone_number' => '/^(\+?\d{1,3}[- ]?)?\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/'

    ];





    public static function validateDates(string $checkIn, string $checkOut): bool

    {

        try {





            if (empty($checkIn) || empty($checkOut)) {

                static::setError('check_in', 'Дата заїзду є обов\'язковою. ');

                static::setError('check_out', 'Дата виїзду є обов\'язковою.');

                return false;

            }



            $checkInDate = DateTime::createFromFormat('Y-m-d', $checkIn);

            $checkOutDate = DateTime::createFromFormat('Y-m-d', $checkOut);



            if (!$checkInDate || !$checkOutDate) {

                static::setError('check_in', 'Некоректний формат дати.');

                static::setError('check_out', 'Некоректний формат дати.');

                return false;

            }



            if ($checkInDate >= $checkOutDate) {

                static::setError('check_out', 'Дата виїзду має бути пізніше дати заїзду.');

                return false;

            }



            return true;

        } catch (Exception $e) {

            static::setError('check_in', 'Помилка обробки дати.');

            static::setError('check_out', 'Помилка обробки дати');

            return false;

        }

    }



    public static function getDateErrors(): array

    {

        $dateErrors = [];



        if (!empty(static::$errors['check_in'])) {

            $dateErrors['check_in'] = static::$errors['check_in'];

        }



        if (!empty(static::$errors['check_out'])) {

            $dateErrors['check_out'] = static::$errors['check_out'];

        }



        return $dateErrors;

    }



}