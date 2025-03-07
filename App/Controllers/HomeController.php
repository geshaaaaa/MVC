<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use Core\Controller;
use Core\View\View;


class HomeController extends Controller
{
    public function index() : array
    {
            View::page('home');

        return $this->response(Status::OK,  ['message' => 'Home loaded']);
    }



}