<?php

namespace App\Controllers;

use App\Enums\SQL\CommandsSQL;
use App\Models\HousingAmenities;
use App\Models\Amenities;
use App\Session\Session;
use Core\Controller;
use Core\View\View;
use App\Enums\Http\Status;



class HousingAmenitiesController extends Controller
{

    private Session $session;

    public function __construct()
    {
        $this->session = new Session();

    }



    public function getHousingAmenities(int $housingId)
    {
      return $amenities = HousingAmenities::select(['amenities.name', 'amenities.icon', 'housing_amenities.value'])
            ->join('amenities', [['left' => 'amenities.id', 'operator' => '=', 'right' => 'housing_amenities.amenity_id']])
            ->where('housing_amenities.housing_id', CommandsSQL::EQUAL, $housingId)
            ->get();

    }


}