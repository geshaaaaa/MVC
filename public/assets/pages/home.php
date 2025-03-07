<?php

use App\Session\Session;
use App\Controllers\AuthController;
use App\Models\User;
$session = new Session();

$errors = $session->getFlash('errors', []);
$errors = array_unique($errors);
$isLogged = new AuthController();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container">
        <nav class="nav">
            <a href="#">Знайти помешкання</a>
            <a href="#">Розмістити своє помешкання</a>
            <a href="#">Посібники з аренди</a>
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
                        <a href="#">Резервації</a>
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
        <h2 class="property-title">Latest added Property Listing</h2>
        <div class="property-grid">
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
        </div>
    </section>


    <section class="top-rated">
        <h2 class="property-title">Top Rated Properties</h2>
        <div class="property-grid">
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <div class="rating">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                </div>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <div class="rating">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                </div>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <div class="rating">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                </div>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
            <div class="property-card">
                <button class="like-wished">
                    <img src="/assets/pages/images/Group (1).png" alt="Liked">
                </button>
                <div class="rating">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                    <img src="/assets/pages/images/star.svg" alt="star">
                </div>
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Radisson Blu Resort Bukovel</p>
                <p class="property-address">100 Smart Street, LA, USA</p>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="hosting-banner">
        <h2 class="host-banner-title">Try Hosting <br> With Us</h2>
                <div class="yellow-button"><a  href="#" class="banner-text-button">Become a host</a>
        </div>
    </section>


    <section class="rental-guides">
        <h2 class="property-title">Тип розміщення</h2>
        <div class="property-grid">
            <div class="property-card">
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Готель</p>
            </div>
            <div class="property-card">
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Апартаменти/Квартири</p>
            </div>
            <div class="property-card">
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Вілли</p>
            </div>
            <div class="property-card">
                <a href="#"><img class="property-image" src="/assets/pages/images/331502027.jpg" alt="Image of property"></a>
                <p class="property-name">Котеджі</p>
            </div>
        </div>
    </section>

    <section class ="Discover-about">
        <h2 class="property-title">
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
