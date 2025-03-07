<?php

namespace App\Validators\HousingValidation;

use App\Validators\BaseValidator;
use DateTime;
use Exception;

class HousingValidator extends BaseValidator
{
    protected static array $rules = [
        'location' => '/^[a-zA-Zа-яА-ЯїєґІ\s\-\p{P}]*$/u',
        'guests_capacity' => '/^\d+$/',
    ];

    protected static array $errors = [
        'location' => 'Местоположение должно содержать только буквы, пробелы и дефисы.',
        'guests_capacity' => 'Количество гостей должно быть числом.',
    ];

    public static function validate(array $fields = [])
    {

        parent::validate($fields);

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