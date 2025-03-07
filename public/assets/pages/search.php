<?php



use App\Controllers\HousingAmenitiesController;

use App\Models\HousingAmenities;

use App\Session\Session;

use App\Controllers\AuthController;

use App\Models\User;

use App\Models\Amenities;



$session = new Session();



$errors = $session->getFlash('errors', []);

$errors = array_unique($errors);

$isLogged = new AuthController();

$amenitiesController = new HousingAmenitiesController();

?>



<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Результаты пошуку</title>

    <link rel="stylesheet" href="/assets/css/search.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header class="header">

        <div class="container">
            <nav class="nav">
                <a href="#">Знайти помешкання</a>
                <a href="#">Розмістити своє помешкання</a>
                <a href="#">Посібники з аренди</a>
                <div class="user-menu">
                    <button class="user-icon" id="userIcon" style="display: <?= $isLogged->isAuth() ? 'none' : 'block' ?>">
                        <img src="/assets/pages/images/bx_bxs-user-circle.png" alt="User Image">
                    </button>
                    <button class="user-icon-logged" id="userIconLogged" style="display: <?= $isLogged->isAuth() ? 'block' : 'none' ?>">
                        <img src="/assets/pages/images/bx_bxs-user-circle.png" alt="User Image">
                    </button>
                    <div class="dropdown-content" id="dropdownContent">
                        <?php if ($isLogged->isAuth()): ?>
                            <a href="#"> <?= $isLogged->isAuth() ? $isLogged->authEmail() : 'Увійдіть' ?></a>
                            <a href="/">Головна</a>
                            <a href="#">Помешкання</a>
                            <a href="#">Резервації</a>
                            <a href="#">Побажання</a>
                            <a href="#">Аккаунт</a>
                            <a href="/logout">Вийти</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
</header>

<main>
    <div class="container">
        <div class="results-header">
            <h2>Знайдено <?php $results = $_SESSION['search_results']?? []; ?>
                <?= count($results) ?> результат(и)</h2>
        </div>

        <div class="filters-container">

            <span class="filter-tag">100 Smart Street ✖</span>

            <span class="filter-tag">12 Май 2024 ✖</span>

            <span class="filter-tag">Короткий период ✖</span>

            <button class="filter-button">

                <img src="/assets/pages/images/Vector.png" alt="Filter Icon"> Фільтри
            </button>
        </div>
        <div class="content">
            <div class="property-list">
                <?php
                $results = $_SESSION['search_results'] ?? [];
                if ($results):
                    foreach ($results as $housing):
                        $amenities = $amenitiesController->getHousingAmenities($housing->id);

                        $isWished = in_array($housing->id, $_SESSION['wishlist'] ?? []);
                        ?>

                        <div class="property-card">

                            <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housing->id ?>">

                                <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>

                            </button>

                            <img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Фото помешкання">

                            <div class="property-info">

                                <p class="property-title"><?= $housing->title ?></p>

                                <p class="property-address"><?= $housing->location ?></p>

                                <p class="property-price">Ціна: <?= $housing->price ?></p>

                                <p class="property-details">
                                    <span>👤 <?= $housing->guests_capacity_max ?></span>
                                    <?php if ($amenities): ?>
                                        <br>  <br>
                                        <?php foreach ($amenities as $amenity): ?>
                                            <span> <img src="/assets/pages/<?= $amenity->icon ?>" alt="<?= $amenity->name ?>" style="width: 20px; height: 20px; vertical-align: middle;">
                                                <?= $amenity->name ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </p>

                            </div>

                        </div>

                    <?php endforeach;

                else: ?>

                    <p>Ничего не найдено.</p>

                <?php endif; ?>

            </div>

        </div>

    </div>

</main>
<script src="/assets/js/scripts.js"></script>
</body>
</html>