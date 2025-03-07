<?php

namespace App\Models;


use Core\Model;

class HousingAmenities extends Model
{
    public static ?string $tableName = 'housing_amenities';
    public int $id,  $value, $housing_id, $amenity_id;
    public string $icon;
    public string $name;


}
