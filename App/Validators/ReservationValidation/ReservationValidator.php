<?php

namespace App\Validators\ReservationValidation;

use App\Validators\BaseValidator;
use DateTime;
use Exception;

class ReservationValidator extends Base
{


    protected static array $errors = [
        'housing_id' => 'Не коректний ID помешкання.',
        'user_id' => 'Не коректний  ID користувача.',
        'status' => 'Не коректний  статус бронювання.',
        'check_in' => 'Некоректна дата заїзду.',
        'check_out' => 'Некоректна дата виїзду.',
        'first_name' => 'Помилка при введені ім\'я',
        'last_name' => 'Помилка при введені прізвища',
        'phone_number' => 'Помилка при введені номера телефону'
    ];

    public static function validate(array $fields = [])
    {
        parent::validate($fields);

        if (isset($fields['check_in']) && isset($fields['check_out'])) {
            if (!static::validateDates($fields['check_in'], $fields['check_out'])) {
                return false;
            }
        }
        return empty(static::$errors);
    }



    public static function getErrors(): array
    {
        return static::$errors;
    }

    public static function setError(string $key, string $message): void
    {
        static::$errors[$key] = $message;
    }

}