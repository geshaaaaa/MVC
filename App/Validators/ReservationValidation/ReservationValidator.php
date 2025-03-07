<?php

namespace App\Validators\ReservationValidation;

use App\Validators\BaseValidator;
use DateTime;
use Exception;

class ReservationValidator extends BaseValidator
{
    protected static array $rules = [
        'housing_id' => '/^\d+$/',
        'user_id' => '/^\d+$/',
        'status' => '/^(pending|confirmed|cancelled)$/',
    ];

    protected static array $errors = [
        'housing_id' => 'Некорректный ID жилья.',
        'user_id' => 'Некорректный ID пользователя.',
        'status' => 'Некорректный статус бронирования.',
        'check_in' => 'Некорректная дата заезда.',
        'check_out' => 'Некорректная дата выезда.',
    ];

    public static function validate(array $fields = [])
    {
        parent::validate($fields);

        if (isset($fields['check_in']) && isset($fields['check_out'])) {
            if (!self::validateDates($fields['check_in'], $fields['check_out'])) {
                return false;
            }
        }
        return empty(static::$errors);
    }

    protected static function validateDates(string $checkIn, string $checkOut): bool
    {
        try {
            $checkInDate = new DateTime($checkIn);
            $checkOutDate = new DateTime($checkOut);

            if ($checkInDate >= $checkOutDate) {
                static::setError('check_out', 'Дата выезда должна быть позже даты заезда.');
                return false;
            }

            return true;
        } catch (Exception $e) {
            static::setError('check_in', 'Некорректный формат даты.');
            static::setError('check_out', 'Некорректный формат даты.');
            return false;
        }
    }
}