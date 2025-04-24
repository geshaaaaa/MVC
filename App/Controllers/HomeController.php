<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use Core\Controller;
use Core\View\View;
use App\Controllers\HousingController;


class HomeController extends Controller
{

    public function index() : array
    {
        $latest = new HousingController;

        $latest->getLatestListings();

        View::page('home');

        return $this->response(Status::OK,  ['message' => 'Home loaded']);
    }



}