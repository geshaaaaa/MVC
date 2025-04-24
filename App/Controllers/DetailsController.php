<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Housing;
use App\Models\User;
use App\Models\Wishlists;
use App\Session\Session;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Models\Images;
use Core\Traits\Queryable;

class DetailsController extends Controller
{

    protected Housing $housingModel;
    protected Images $imageModel;
    protected Session $session;
    protected User $userModel;
    protected AuthController $authController;
    public function __construct()
    {
        $this->housingModel = new Housing();
        $this->imageModel = new Images();
        $this->session = new Session();
        $this->authController = new AuthController();
        $this->userModel = new User();
    }


    public function show()
    {
        $housingId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$housingId) {

            $this->session->set('errors', ['Некорректный ID жилья.']);
            Redirect::to('/');
            exit;
        }

        $housing = $this->housingModel::find($housingId);

        if (!$housing) {
            // Если жилье не найдено
            $this->session->set('errors', ['Запрашиваемое жилье не найдено.']);
            Redirect::to('/');
            exit;
        }


        $images = $this->imageModel::where('housing_id', CommandsSQL::EQUAL, $housingId)->get();



        $likeCount = Wishlists::count(['housing_id' => $housingId]);

        View::page("details", ['housing' => $housing, 'images' => $images,  'likeCount' => $likeCount, ]);
        exit;

    }

}