<?php

namespace App\Models;

use Core\Model;

class Wishlists extends Model
{
    public static ?string $tableName = 'wishlists';
    public int $id, $user_id, $housing_id;

}