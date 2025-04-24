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

$filters = $_SESSION['applied_filters'] ?? [];


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

    <title>Результаты пошуку</title>

    <link rel="stylesheet" href="/assets/css/search.css">

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

<main>
    <div class="container">
        <div class="results-header">
            <h2>Знайдено <?php $results = $_SESSION['search_results']?? []; ?>
                <?= count($results) ?> результат(и)</h2>
        </div>

        <div class="filters-container">
            <div class="filter-header">
                <?php
                $hasFilters = false;
                foreach ($filters as $values) {
                    if (is_array($values)) {
                        $uniqueValues = array_unique(array_filter($values, fn($value) => !empty($value)));
                        if (!empty($uniqueValues)) {
                            $hasFilters = true;
                            break;
                        }
                    } else {
                        if (!empty($values)) {
                            $hasFilters = true;
                            break;
                        }
                    }
                }

                if ($hasFilters):
                    foreach ($filters as $key => $values):
                        if (is_array($values)):
                            $uniqueValues = array_unique(array_filter($values, fn($value) => !empty($value)));
                            foreach ($uniqueValues as $value):
                                ?>
                                <span class="filter-tag">
                            <?= htmlspecialchars(ucfirst($value ?? '')) ?>
                            <a href="/removeFilter?filter=<?= $key ?>&value=<?= urlencode($value) ?>" class="remove-filter">✕</a>
                        </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php if (!empty($values)): ?>
                                <span class="filter-tag">
                            <?= htmlspecialchars(ucfirst($values ?? '')) ?>
                            <a href="/removeFilter?filter=<?= $key ?>" class="remove-filter">✕</a>
                        </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="filter-tag">Фільтри не обрані</span>
                <?php endif; ?>
            </div>
            <button class="filter-button">
                <img src="/assets/pages/images/Filter-Btn.png" alt="Filter Icon">
            </button>
        </div>
        <div class="filter-modal">
            <form action="api/housing-filter" method="get">
                <div class="filter-content">
                    <span class="close-filter">&times;</span>
                    <h3>Фільтри</h3>

                    <!-- Фильтр для локации -->
                    <div class="filter-group">
                        <h4>Локація</h4>
                        <input type="text" name="location" id="location" placeholder="Введіть локацію">
                    </div>

                    <!-- Фильтр для количества гостей -->
                    <div class="filter-group">
                        <h4>Кількість гостей</h4>
                        <input type="number" name="guests_capacity" id="guests_capacity" placeholder="Кількість гостей">
                    </div>

                    <div class="filter-group">
                        <h4>Тип житла</h4>
                        <label><input type="checkbox" name="type[]" value="hotel"> Готель</label>
                        <label><input type="checkbox" name="type[]" value="villa"> Вілла</label>
                        <label><input type="checkbox" name="type[]" value="apartment"> Апартаменти</label>
                    </div>

                    <div class="filter-group">
                        <h4>Зручності</h4>
                        <label><input type="checkbox" name="amenity[]" value="WIFI"> Wi-Fi</label>
                        <label><input type="checkbox" name="amenity[]" value="Parking"> Паркінг</label>
                        <label><input type="checkbox" name="amenity[]" value="Television"> Телебачення</label>
                        <label><input type="checkbox" name="amenity[]" value="Elevator"> Ліфт</label>
                        <label><input type="checkbox" name="amenity[]" value="Pets-friendly"> Дозволено з тваринами</label>
                        <label><input type="checkbox" name="amenity[]" value="washer"> Пральна машина</label>
                        <label><input type="checkbox" name="amenity[]" value="balcony"> Балкон</label>
                        <label><input type="checkbox" name="amenity[]" value="cleaner"> Прибиральник</label>
                    </div>

                    <div class="filter-group">
                        <h4>Ціна</h4>
                        <input type="number" name="min-price" id="min-price" placeholder="Мін. ціна">
                        <input type="number" name="max-price" id="max-price" placeholder="Макс. ціна">
                    </div>

                    <button type="submit" class="apply-filters">Застосувати зміни</button>
                </div>
            </form>
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

                        <div class="property-card" data-housing-id="<?= $housing->id ?>">

                            <button class="like-wished <?= $isWished ? 'active' : '' ?>" data-housing-id="<?= $housing->id ?>">

                                <div class="heart <?= $isWished ? 'heart-active' : '' ?>"></div>

                            </button>

                              <div class="property-slider">
                                </div>

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

    </div>

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