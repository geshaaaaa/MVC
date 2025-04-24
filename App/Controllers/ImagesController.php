<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Images;
use Core\Controller;

class ImagesController extends Controller
{
    public function insertImages()
    {
        $fields = requestBody();

        if (empty($_FILES['images'])) {
            return $this->response(Status::BAD_REQUEST,['error' => 'Invalid request']);
        }

        if (!empty($fields['housing_id']) && empty($fields['user_id'])) {
            $entity = ['housing_id' => $fields['housing_id']];
        } elseif (!empty($fields['user_id']) && empty($fields['housing_id'])) {
            $entity = ['user_id' => $fields['user_id']];
        } else {
            return $this->response(Status::NOT_FOUND, ['error' => 'Specify either housing_id or user_id']);
        }

        $uploadedImages = [];

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = time() . '_' . $_FILES['images']['name'][$key];
            $filePath = "/assets/pages/images/housing_photo/" . $fileName;

            if (move_uploaded_file($tmp_name, $filePath)) {
                $uploadedImages[] = array_merge($entity, ['image_url' => $filePath]);
            }
        }

        if (!empty($uploadedImages)) {
            foreach ($uploadedImages as $image) {
                Images::create($image);
            }
            return $this->response(Status::CREATED,['message' => 'Images uploaded successfully']);
        }

        return $this->response(Status::INTERNAL_SERVER_ERROR,['error' => 'Failed to upload images']);
    }



    public function showImages()
    {
        $housing_id = $_GET['housing_id'] ?? null;
        $user_id = $_GET['user_id'] ?? null;


        // Проверяем, что housing_id или user_id передан
        if ($housing_id) {
            // Получаем изображения для конкретного жилья
            $images = Images::where('housing_id', CommandsSQL::EQUAL, $housing_id)->get();
        } elseif ($user_id) {
            // Если передан user_id, получаем изображения для пользователя
            $images = Images::where('user_id', CommandsSQL::EQUAL, $user_id)->get();
        } else {
            return $this->response(Status::BAD_REQUEST, ['error' => 'Specify housing_id or user_id']);
        }

        // Проверяем, что изображения найдены
        if (empty($images)) {
            return $this->response(Status::OK, ['data' => []]); // Вернем объект с пустым массивом в поле data
        }

        // Преобразуем данные изображений в массив URL
        $imageUrls = [];
        foreach ($images as $image) {
            $imageUrls[] = $image->image_url;  // Важно убедиться, что image_url возвращает правильный путь
        }

        return $this->response(Status::OK, $imageUrls );
    }
}