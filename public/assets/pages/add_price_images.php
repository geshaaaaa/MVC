<?php

use App\Controllers\AuthController;
use App\Models\Housing;
use App\Session\Session;

$session = new Session();

$errors = $session->getFlash('errors', []);

$errors = array_unique($errors);

$isLogged = new AuthController();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати нерухомість</title>
    <link rel="stylesheet" href="/assets/css/add_price_images.css">
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
            <a href="#" class="header-text">Розмістити своє помешкання</a>
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

<form id="housingForm" action="/add-housing-price-images" method="post" enctype="multipart/form-data">
    <div class="content">
        <h2>Ціна та фотографії об'єкта</h2>

        <div class="form-group">
            <label for="price">Ціна за ніч:</label>
            <input type="number" name="price" id="price" min="0" required>
        </div>

        <div class="form-group">
            <label for="images">Фотографії об'єкта:</label>

            <div class="custom-file-wrapper">
                <input type="file" name="images[]" id="images" multiple accept="image/*" style="display: none;" required>
                <button type="button" id="customFileButton">Вибрати файли</button>
                <span id="fileName">Файл не вибрано</span>
            </div>

            <!-- Место для вывода ошибки -->
            <div id="fileError" style="color: red; margin-top: 8px;"></div>
        </div>

        <button type="submit">Далі</button>
    </div>
</form>



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
<script src="/assets/js/add_price.js"></script>
</body>
</html>