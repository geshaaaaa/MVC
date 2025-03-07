<?php

namespace App\Models;

use Core\Model;

class Amenities extends Model
{
    public static ?string $tableName = 'amenities';
    public int $id;
    public string $name;
    public string $icon;

}