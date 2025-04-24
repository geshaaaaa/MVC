<?php

namespace App\Models;

use Core\Model;

class Images extends Model
{
    public static ?string $tableName = 'images';
    public int $id;
    public ?int $user_id, $housing_id;
    public string $image_url;

}