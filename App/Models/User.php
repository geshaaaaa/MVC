<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static ?string $tableName = 'users';
    public string $email, $password, $created_at, $role;


    public ?string $token;
    public ?int $token_expired_at, $owner_id;
    public function getAllInfo(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}