<?php

namespace App\Validators\OwnerValidation;

use App\Validators\BaseValidator;

class OwnerValidator extends BaseValidator
{
    protected static array $rules = [
        'first_name' => '/^[a-zA-Zа-яА-ЯїєґІі\s\-]+$/u',
        'last_name' => '/^[a-zA-Zа-яА-ЯїєґІі\s\-]+$/u',
        'phone_number' => '/^(\+?\d{1,3}[- ]?)?\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/'
    ];

    protected static array $errors = [
        'first_name' => 'Помилка при введені ім\'я',
        'last_name' => 'Помилка при введені прізвища',
        'phone_number' => 'Помилка при введені номера телефону'
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