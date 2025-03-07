<?php

namespace App\Models;

use Core\Model;
use DateTime;

class Reservations extends Model
{
    public static ?string $tableName = 'reservations';
    public int $id, $user_id, $housing_id;
    public DateTime $check_in, $check_out;
    public string $status;


}