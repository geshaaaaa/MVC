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
$showModal = !empty($errors);
unset($_SESSION['errors']);

$isLogged = new AuthController();

$amenitiesController = new HousingAmenitiesController();

$filters = $_SESSION['applied_filters'] ?? [];



$housingTitle = htmlspecialchars($housing->title ?? 'Название жилья');
$housingDescription = htmlspecialchars($housing->description ?? '');
$housingPrice = number_format($housing->price ?? 0, 2, '.', ' ');
$housingLocation = htmlspecialchars($housing->location ?? '');

$images = $images ?? [];

$housingId = $housing->id;
$likeCount = $likeCount ?? 0;

$amenities = $amenitiesController->getHousingAmenities($housingId);
$showAddProperty = false;


if ($isLogged->isAuth()) {
    $user = User::find($isLogged->authId());
    if ($user && in_array($user->role, ['hotel_staff', 'admin'])) {
        $showAddProperty = true;
    }
}


?>



<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $housingTitle ?></title>

    <link rel="stylesheet" href="/assets/css/details.css">

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



    <div class="housing-images">
        <?php if (!empty($images)): ?>
            <div class="main-image">
                <?php if (!empty($images[0])): ?>
                    <img src="<?= htmlspecialchars($images[0]->image_url) ?>" alt="<?= $housingTitle ?> - Головне фото">
                <?php else: ?>
                    <p>Нема головного фото.</p>
                <?php endif; ?>
            </div>
            <div class="side-images">
                <?php
                $imageCount = count($images);
                for ($i = 1; $i < min($imageCount, 4); $i++): ?>
                    <div class="side-image" data-index="<?= $i ?>">
                        <?php if (isset($images[$i])): ?>
                            <img src="<?= htmlspecialchars($images[$i]->image_url) ?>" alt="<?= $housingTitle ?> - Фото <?= $i + 1 ?>">
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
                <?php if ($imageCount > 4): ?>
                    <div class="side-image more-photos">
                        <b>+<?= $imageCount - 4 ?> Фото</b>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Нет доступных фотографий.</p>
        <?php endif; ?>
    </div>

    <div id="galleryModal" class="modal">
        <div class="modal-content-gallery">
            <span class="close-gallery">&times;</span>
            <div class="all-images-grid">
                <?php foreach ($images as $index => $image): ?>
                    <div class="grid-image" data-index="<?= $index ?>">
                        <img src="<?= htmlspecialchars($image->image_url) ?>" alt="<?= $housingTitle ?> - Фото <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
        <a class="prev" onclick="changeImage(-1)">&#10094;</a>
        <a class="next" onclick="changeImage(1)">&#10095;</a>
    </div>

        <div class="housing-details">
            <div class="description-without-resrvform">
                <div class="title-like">
                    <?php $isWished = in_array($housingId, $_SESSION['wishlist'] ?? []); ?>
                    <h1 class="housing-title"><?= $housingTitle ?></h1>
                    <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housingId ?>">
                        <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>
                        <div class="count-likes"><?= $likeCount ?></div>
                    </button>
                    <div class="review-form">
                        <?php if ($isLogged->isAuth()): ?>
                            <div class="rating" data-housing-id="<?= $housing->id ?>">
                                <div class="rating_item" data-item-value="5">★</div>
                                <div class="rating_item" data-item-value="4">★</div>
                                <div class="rating_item" data-item-value="3">★</div>
                                <div class="rating_item" data-item-value="2">★</div>
                                <div class="rating_item" data-item-value="1">★</div>
                            </div>
                        <?php endif; ?>
                        <span class="avg-rating" id="avgRating-<?= $housing->id ?>"></span>
                    </div>
                </div>

                <div class="location-details">
                    <p><?= $housingLocation ?></p>
                </div>

                <div class="housing-description">
                    <h1 class="housing-description">Опис </h1>
                    <p><?= $housingDescription ?></p>
                </div>

                <div class="amenities-details">
                    <h2 class="amenities-title">Зручності</h2>
                    <?php if ($amenities): ?>
                        <div class="amenities-list">
                            <?php foreach ($amenities as $amenity): ?>
                                <div class="amenity-item">
                                    <img src="/assets/pages/<?= $amenity->icon ?>" alt="<?= $amenity->name ?>" class="amenity-icon">
                                    <span class="amenity-name"><?= $amenity->name ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Немає доступних зручностей.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="reservation-info-box">
                <div class="price-range">
                    <?php if (isset($housing->price_short) && isset($housing->price_long)): ?>
                        <p class="price-label">Ціновий діапазон:</p>
                        <p class="price-value"><?= number_format($housing->price_short, 0, '.', ' ') ?> - <?= number_format($housing->price_long, 0, '.', ' ') ?> грн</p>
                    <?php elseif (isset($housing->price)): ?>
                        <p class="price-label">Ціна за добу:</p>
                        <p class="price-value"><?= number_format($housing->price, 0, '.', ' ') ?> грн</p>
                    <?php else: ?>
                        <p>Ціна не вказана</p>
                    <?php endif; ?>
                    <?php if (isset($housing->price_short)): ?>
                        <p class="period-price">Короткий період: <span class="price-value"><?= number_format($housing->price_short, 0, '.', ' ') ?></span> грн</p>
                    <?php endif; ?>
                    <?php if (isset($housing->price_medium)): ?>
                        <p class="period-price">Середній період: <span class="price-value"><?= number_format($housing->price_medium, 0, '.', ' ') ?></span> грн</p>
                    <?php endif; ?>
                    <?php if (isset($housing->price_long)): ?>
                        <p class="period-price">Довгий період: <span class="price-value"><?= number_format($housing->price_long, 0, '.', ' ') ?></span> грн</p>
                    <?php endif; ?>
                </div>
                <button class="reserve-button" data-housing-id="<?= $housingId ?>">Резервувати</button>
            </div>
        </div>

    <div id="reservationModal" class="modal">
        <div class="modal-content-reservation">
            <span class="close-modal">&times;</span>
            <h2>Резервування</h2>
            <div class="reservation-details">
                <p>Ціна за добу: <span id="dailyPrice"><?= number_format($housing->price ?? 0, 2, '.', ' ') ?></span> грн</p>

                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form id="reservationForm" action="/api/save-reservation-data" method="POST">
                    <input type="hidden" name="housing_id" id="housingId" value="<?= $housingId ?>">
                    <input type="hidden" name="total_price" id="totalPriceInput" value="0.00">

                    <div class="date-picker">
                        <label for="checkinDate">Дата заїзду:</label>
                        <input type="date" id="checkinDate" name="check_in" >
                    </div>
                    <div class="date-picker">
                        <label for="checkoutDate">Дата виїзду:</label>
                        <input type="date" id="checkoutDate" name="check_out">
                    </div>

                    <p id="totalDays" style="margin-top: 10px; font-weight: bold;"></p>
                    <p>Загальна вартість: <span id="totalPrice" style="font-weight: bold;">0.00</span> грн</p>

                    <button type="submit" id="reserveButton">Перейти до резервації</button>
                </form>
            </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let showModal = <?= json_encode($showModal) ?>;
            if (showModal) {
                document.getElementById("reservationModal").style.display = "block";
            }
        });
    </script>
    <script src="/assets/js/wishlist.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/details.js"></script>
</body>
</html>