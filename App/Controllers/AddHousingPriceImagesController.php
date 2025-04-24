<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Models\Housing;
use App\Models\Images;
use App\Models\Owner;
use App\Models\User;
use App\Session\Session;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Controllers\AuthController;
use ReallySimpleJWT\Token;


class AddHousingPriceImagesController extends Controller
{


    private Session $session;
    public function __construct()
    {
        $this->session = new Session();

    }



    public function index()
    {
        View::page('add_price_images');
        exit();
    }

    public function store()
    {
        $price = $_POST['price'];
        $images = $_FILES['images'];
        $housingData = $this->session->get('housing_data');

        $ownerData = $this->session->get('owner_data');


        $housingData['price'] = $price;

        $ownerId = Owner::createAndReturn($ownerData)->id;
        $userId = $this->getUserIdFromToken();


        User::find($userId)->update(['owner_id' => $ownerId]);

        $housingData['owner_id'] = $ownerId;

        // Створюємо запис в таблиці housing
        $housingId = Housing::createAndReturn($housingData)->id;

        // Зберігаємо housing_id в сесію
        $this->session->set('housing_id', $housingId);

        // Завантажуємо зображення та зберігаємо їх в базу даних
        $uploadedImages = $this->uploadImages($images, $housingId);

        if ($uploadedImages) {
            Redirect::to('/add_amenities');
        } else {
            // Обробка помилки завантаження зображень
          return $this->response(Status::UNPROCESSABLE_ENTITY, ['error' => 'Помилка, щось пішло не так']);
        }

        exit();
    }

    private function uploadImages(array $files, int $housingId): bool
    {
        $uploadDir = 'assets/pages/images/housing_photo/';
        $uploaded = true;

        foreach ($files['name'] as $key => $name) {
            $tmpName = $files['tmp_name'][$key];
            $error = $files['error'][$key];

            if ($error === UPLOAD_ERR_OK) {
                $uniqueName = uniqid() . '_' . $name;
                $destination = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmpName, $destination)) {
                    // Зберігаємо інформацію про зображення в базу даних
                    Images::create([
                        'housing_id' => $housingId,
                        'image_url' => $destination,
                    ]);
                } else {
                    $uploaded = false;
                }
            } else {
                $uploaded = false;
            }
        }

        return $uploaded;
    }


    private function getUserIdFromToken(): int
    {
        $token = $_COOKIE['token'];
        $payload = Token::getPayload($token);
        return $payload['user_id'];
    }



}