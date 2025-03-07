<?php

namespace App\Models;

use Core\Model;

class Housing extends Model
{
    public static ?string $tableName = 'housing';
    public int $id, $users_id, $guests_capacity_max;

    public string $type, $title, $location, $created_at; // ENUM('hotel', 'villa', 'apartment') NOT NULL
    public ?string $description;
    public float $price;


}