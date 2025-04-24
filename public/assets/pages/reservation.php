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

$reservationData = $_SESSION['reservation_data'] ?? [];



$housingId = $reservationData["housing_id"] ?? null;

$checkin = $reservationData["check_in"] ?? null;
$checkout = $reservationData["check_out"] ?? null;
$totalPrice = $reservationData["total_price"] ?? null;


if ($isLogged->isAuth()) {
    $user = User::find($isLogged->authId());
    $showAddProperty = in_array($user->role, ['hotel_staff', 'admin']);
}



?>



<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="/assets/css/reservation.css">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

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
                        <a href="/user_reservation">Резервації</a>
                        <a href="#">Побажання</a>
                        <a href="#">Аккаунт</a>
                        <a href="/logout">Вийти</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</header>

<a href="/" class="home-button">Повернутись на головну</a>
<h2 class="reservation-header">Бронювання</h2>


<?php if (!empty($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>



<div class="reservation-container">
    <form action="/api/reservation-form" method="POST" class="reservation-form">
        <input type="hidden" name="housing_id" value="<?= $housingId ?>">
        <input type="hidden" name="check_in" value="<?= $checkin ?>">
        <input type="hidden" name="check_out" value="<?= $checkout ?>">
        <input type="hidden" name="total_price" value="<?= $totalPrice ?>">

        <label>Ім'я:</label>
        <input type="text" name="first_name">

        <label>Прізвище:</label>
        <input type="text" name="last_name">

        <label>Телефон:</label>
        <input type="tel" name="phone_number">

        <label>Побажання:</label>
        <input type="text" name="notes">

        <button type="submit">Підтвердити бронювання</button>

    </form>


    <div class="reservation-image-block">
        <?php
        $housing = $_SESSION['reservation_data'] ?? null;
        if ($housing):
            ?>
            <div class="property-card" data-housing-id="<?= $housing['housing_id'] ?>">
                <div class="property-slider"></div>
                <div class="property-info">
                    <h3><?= htmlspecialchars($housing['title'] ?? 'Неизвестное жилье') ?></h3>
                    <p><?= htmlspecialchars($housing['location'] ?? 'Локация не указана') ?></p>
                    <p><strong>Ціна за весь період:</strong> <?= htmlspecialchars($housing['total_price']) ?> грн</p>
                </div>
            </div>
        <?php else: ?>
            <p>Нічого не знайдено</p>
        <?php endif; ?>
    </div>
</div>

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
<script src="/assets/js/wishlist.js"></script>
<script src="/assets/js/scripts.js"></script>
<script src="/assets/js/details.js"></script>
</body>
</html>