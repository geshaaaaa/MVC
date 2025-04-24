<?php

namespace App\Models;

use Core\Model;
use DateTime;

class Reservations extends Model
{
    public static ?string $tableName = 'reservations';
    public int $id, $users_id, $housing_id;
    public string $check_in, $check_out;

    public string $status;
    public string $first_name;
    public string $last_name;
    public string $phone_number;
    public float $total_price;
    public ?string  $notes;


}