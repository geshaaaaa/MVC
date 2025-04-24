<?php

use App\Session\Session;
use App\Controllers\AuthController;
use App\Models\User;
use App\Controllers\HousingAmenitiesController;
$session = new Session();


$errors = $session->getFlash('errors', []);
$errors = array_unique($errors);

$isLogged = new AuthController();
$amenitiesController = new HousingAmenitiesController();

$showAddProperty = false;

if ($isLogged->isAuth()) {
    $user = User::find($isLogged->authId());
    if ($user && in_array($user->role, ['hotel_staff', 'admin'])) {
        $showAddProperty = true;
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
</head>
<body>
<header class="header">
    <div class="container-header">
        <nav class="nav">
            <a href="/properties" class="header-text">Знайти помешкання</a>
            <?php if ($showAddProperty): ?>
                <a href="/add_property" class="header-text">Розмістити своє помешкання</a>
            <?php endif; ?>
            <a href="#" class="header-text">Посібники з аренди</a>
                <button class="user-icon" id="userIcon" style="display: <?= $isLogged->isAuth() ? 'none' : 'block' ?>">
                    <img src="/assets/pages/images/bx_bxs-user-circle.png" alt="User Image">
                </button>
                <div class="user-menu">
                    <button class="user-icon-logged" id="userIconLogged" style="display: <?= $isLogged->isAuth() ? 'block' : 'none' ?>">
                        <img src="/assets/pages/images/bx_bxs-user-circle.png" alt="User Image">
                    </button>
                    <div class="dropdown-content" id="dropdownContent">
                   <?php if ($isLogged->isAuth()): ?>
                        <a href="#"> <?= $isLogged->isAuth() ? $isLogged->authEmail() : 'Увійдіть' ?></a>
                       <a href="/">Головна</a>
                        <a href="#">Помешкання</a>
                        <a href="/user_reservation">Резервації</a>
                        <a href="#">Побажання</a>
                        <a href="#">Аккаунт</a>
                        <a href="/logout">Вийти</a>
                   <?php endif; ?>
                    </div>
                </div>
        </nav>
    </div>
    <section class="banner">
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="search-form" action="/api/search" method="get">
            <div class="search-field">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Which city do you prefer?">
            </div>
            <div class="search-field">
                <label for="check-in">Check In</label>
                <input type="date" id="check-in" name="check_in" placeholder="Add Dates">
            </div>
            <div class="search-field">
                <label for="check-out">Check Out</label>
                <input type="date" id="check-out" name="check_out" placeholder="Add Dates">
            </div>
            <div class="search-field last">
                <label for="guests">Guests</label>
                <input type="number" id="guests" name="guests_capacity" placeholder="Add Guests">
            </div>
            <button type="submit" class="search-button">
                <img src="/assets/pages/images/round-search.png" alt="Search">
            </button>
        </form>
        <div id="results-container">
        </div>
    </section>
</header>

<main>
    <section class="latest-listing">
        <h2 class="property-heading">Нещодавно додані пропозиції</h2>
        <div class="content">
            <div class="property-list">
            <?php
            $latestListings = $_SESSION['latest_listings'] ?? [];

            if (!empty($latestListings)):
                foreach ($latestListings as $housing):
                    $amenities = $amenitiesController->getHousingAmenities($housing->id);
                    $isWished = in_array($housing->id, $_SESSION['wishlist'] ?? []);
                    ?>
                    <div class="property-card" data-housing-id="<?= $housing->id ?>">
                        <div class="review-form">
                            <?php if ($isLogged->isAuth()): ?>
                                <div class="rating" data-housing-id="<?= $housing->id ?>">
                                    <div class="rating_item" data-item-value="5">★</div>
                                    <div class="rating_item" data-item-value="4">★</div>
                                    <div class="rating_item" data-item-value="3">★</div>
                                    <div class="rating_item" data-item-value="2">★</div>
                                    <div class="rating_item" data-item-value="1">★</div>
                                </div>
                            <?php else: ?>
                                <a href="#" class="header-text" onclick="alert('Будь ласка, увійдіть до системи, щоб поставити оцінку.'); return false;">  <div class="rating" data-housing-id="<?= $housing->id ?>">
                                        <div class="rating_item" data-item-value="5">★</div>
                                        <div class="rating_item" data-item-value="4">★</div>
                                        <div class="rating_item" data-item-value="3">★</div>
                                        <div class="rating_item" data-item-value="2">★</div>
                                        <div class="rating_item" data-item-value="1">★</div>
                                    </div></a>
                            <?php endif; ?>
                        </div>
                        <?php if ($isLogged->isAuth()): ?>
                        <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housing->id ?>">
                            <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>
                        </button>
                        <?php else: ?>
                        <a href="#" class="header-text" onclick="alert('Будь ласка, увійдіть до системи, щоб поставити оцінку.'); return false;">
                        <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housing->id ?>">
                            <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>
                        </button>
                        </a>
                        <?php endif; ?>
                        <a href="/details?id=<?= $housing->id ?>" class="property-link-slider">
                            <div class="property-slider">
                            </div>
                        </a>
                        <div class="property-info">
                            <a href="/details?id=<?= $housing->id ?>" class="property-link-title">
                                <p class="property-title"><?= htmlspecialchars($housing->title) ?></p>
                                <p class="property-address"><?= htmlspecialchars($housing->location) ?></p>
                            </a>
                        <p class="property-details">
                            <span>Кількість гостей: 👤 <?= $housing->guests_capacity_max ?></span>
                            <?php if (!empty($amenities)): ?>
                                <br><br>
                                <?php foreach ($amenities as $amenity): ?>
                                    <span>
                                    <img src="/assets/pages/<?= htmlspecialchars($amenity->icon) ?>"
                                         alt="<?= htmlspecialchars($amenity->name) ?>"
                                         style="width: 20px; height: 20px; vertical-align: middle;">
                                </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </p>
                        </div>
                    </div>
                <?php endforeach;
            else: ?>
                <p>Нічого не знайдено</p>
            <?php endif; ?>
        </div>
        </div>
    </section>


    <section class="top-rated">
        <h2 class="property-heading">Top Rated Properties</h2>
        <div class="content">
            <div class="property-list">
                <?php
                $latestListings = $_SESSION['latest_listings'] ?? [];

                if (!empty($latestListings)):
                    foreach ($latestListings as $housing):
                        $amenities = $amenitiesController->getHousingAmenities($housing->id);
                        $isWished = in_array($housing->id, $_SESSION['wishlist'] ?? []);
                        ?>
                        <div class="property-card" data-housing-id="<?= $housing->id ?>">
                            <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housing->id ?>">
                                <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>
                            </button>
                            <a href="/details?id=<?= $housing->id ?>" class="property-link-slider">
                                <div class="property-slider">
                                </div>
                            </a>
                            <div class="property-info">
                                <a href="/details?id=<?= $housing->id ?>" class="property-link-title">
                                    <p class="property-title"><?= htmlspecialchars($housing->title) ?></p>
                                    <p class="property-address"><?= htmlspecialchars($housing->location) ?></p>
                                </a>
                                <p class="property-details">
                                    <span>Кількість гостей: 👤 <?= $housing->guests_capacity_max ?></span>
                                    <?php if (!empty($amenities)): ?>
                                        <br><br>
                                        <?php foreach ($amenities as $amenity): ?>
                                            <span>
                                    <img src="/assets/pages/<?= htmlspecialchars($amenity->icon) ?>"
                                         alt="<?= htmlspecialchars($amenity->name) ?>"
                                         style="width: 20px; height: 20px; vertical-align: middle;">
                                </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach;
                else: ?>
                    <p>Нічого не знайдено</p>
                <?php endif; ?>
            </div>
        </div>
    </section>



    <section class="rental-guides">
        <h2 class="property-heading">Тип розміщення</h2>
        <div class="content">
            <div class="property-list">
                    <a href="/properties?type[]=villa" class="property-card rental-card">
                        <div class="rental-image">
                            <img src="/assets/pages/images/housing_photo/620168315.jpeg" alt="Вилла">
                        </div>
                        <div class="rental-type-title">Вілла</div>
                    </a>
                <a href="/properties?type[]=hotel" class="property-card rental-card">
                    <div class="rental-image">
                        <img src="/assets/pages/images/housing_photo/595550862.jpeg" alt="Отель">
                    </div>
                    <div class="rental-type-title">Готель</div>
                </a>
                <a href="/properties?type[]=apartment" class="property-card rental-card">
                    <div class="rental-image">
                        <img src="/assets/pages/images/housing_photo/595548591.jpeg" alt="Апартаменты">
                    </div>
                    <div class="rental-type-title">Апартаменти</div>
                </a>
            </div>
        </div>
    </section>

    <section class ="Discover-about">
        <h2 class="property-heading">
            Дізнайтеся більше про оренду <нерухомості></нерухомості></h2>
        <div class="discover-text-button">
        <div class="Learn-more-text">
            Наші послуги – ваш комфорт та незабутній відпочинок
            Ми пропонуємо широкий вибір житла для вашого відпочинку: <br>від затишних готелів до просторих котеджів та вілл, а також комфортні апартаменти.<br> Наш сайт створений, щоб допомогти вам знайти ідеальне місце для проживання в будь-яку пору року.
        </div>
        <div class="yellow-button"><a  href="#" class="banner-text-button">Дізнатись більше</a></div>
        </div>
    </section>


</main>

<footer class="footer">
    <div class="container-footer">
        <div class="footer-left">
            <div class="logo">LOGO</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div class="footer-center">
            <h3>Компанія</h3>
            <ul >
                <li><a href="#">Про нас</a></li>
                <li><a href="#">Контакти</a></li>
                <li><a href="#">Блог</a></li>
            </ul>
        </div>
        <div class="footer-center">
            <h3>Допомога</h3>
            <ul class="footer-questions">
                <li ><a href="#">Знайти нерухомість</a></li>
                <li><a href="#">Як стати власником?</a></li>
                <li><a href="#">Чому ми?</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <h3>Контакти</h3>
            <p>Телефон: 1234567890</p>
            <p>Email: company@email.com</p>
            <p>Адреса: 100 Smart Street, LA, USA</p>
            <div class="social-media">
                <a href="#"><img src="/assets/pages/images/fb icon.png" alt="Facebook"></a>
                <a href="#"><img src="/assets/pages/images/insta icon.png" alt="Instagram"></a>
                <a href="#"><img src="/assets/pages/images/twitter icon.png" alt="Twitter"></a>
                <a href="#"><img src="/assets/pages/images/linkedin icoon.png" alt="LinkeDin"></a>
            </div>
        </div>
    </div>
</footer>

<script src="/assets/js/scripts.js"></script>
</body>
</html>
