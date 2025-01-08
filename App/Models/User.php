<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static ?string $tableName = 'users';
    public string $email, $password, $created_at, $userType;

    public ?string $token, $token_expired_at;

    public function getAllInfo(): array
    {
        return [
            'userType' => $this->userType,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}