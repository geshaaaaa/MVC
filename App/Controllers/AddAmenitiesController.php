<?php

namespace App\Controllers;

use App\Models\Amenities;
use App\Session\Session;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Models\HousingAmenities;
use App\Controllers\AuthController;

class AddAmenitiesController extends Controller
{
    private Session $session;
    public function __construct()
    {
        $this->session = new Session();

    }

    public function index()
    {
        $amenities = Amenities::all();

        View::page('add_amenities',  ['amenities' => $amenities]);
        exit();
    }

    public function store()
    {
        $fields = requestBody();

        $selectedAmenities = $fields['amenities'];



        $housingId = $this->session->get('housing_id');



        HousingAmenities::deleteWhere('housing_id', $housingId);


        foreach ($selectedAmenities as $amenityId) {
           HousingAmenities::create([
                'housing_id' => $housingId,
                'amenity_id' => $amenityId,
                'value' => 1,
            ]);
        }

        Redirect::to('/');
        exit();
    }

}