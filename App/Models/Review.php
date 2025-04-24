<?php

namespace App\Models;

use Core\Model;

class Review extends Model
{
    protected static ?string $tableName = 'Review';

    public int $user_id, $id, $housing_id;
    public ?int $rating;

    public ?string $comment;
    public string  $created_at;

}