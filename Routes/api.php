<?php

use App\Controllers\AddAmenitiesController;
use App\Controllers\AddHousingPriceImagesController;
use App\Controllers\AddPropertyStep2Controller;
use App\Controllers\AddPropertyStep3Controller;
use App\Controllers\AddPropertyStepController;
use App\Controllers\AuthController;
use App\Controllers\DetailsController;
use App\Controllers\FoldersController;
use App\Controllers\HousingController;
use App\Controllers\ImagesController;
use App\Controllers\NotesController;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;
use App\Controllers\ReservationController;
use App\Controllers\UserReservationController;
use App\Controllers\WishlistsController;
use App\Middleware\UserReservationMiddleware;
use Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\PropertiesController;

Router::get('')
    ->controller(HomeController::class)
    ->actions('index')
    ->middleware([AuthMiddleware::class]);


Router::post('api/register')
    ->controller(RegisterController::class)
    ->actions('register');

Router::get('register')
    ->controller(RegisterController::class)
    ->actions('index');

Router::get('auth')
    ->controller(AuthController::class)
    ->actions('index');

Router::post('api/auth')
    ->controller(AuthController::class)
    ->actions('auth');

Router::get('logout')
    ->controller(AuthController::class)
    ->actions('logout');

Router::get('search')
    ->controller(HousingController::class)
    ->actions('index');

Router::get('api/search')
    ->controller(HousingController::class)
    ->actions('searchHousing');

Router::post('wishlist/toggle')
    ->controller(WishlistsController::class)
    ->actions('toggleWishlist');

Router::get('api/showImages')
    ->controller(ImagesController::class)
    ->actions('showImages');

Router::get('api/housing-filter')
    ->controller(HousingController::class)
    ->actions('housingFilter');


Router::get('removeFilter')
    ->controller(HousingController::class)
    ->actions('removeFilter');

Router::get('properties')
    ->controller(PropertiesController::class)
    ->actions('index');

Router::get('api/properties-filter')
    ->controller(PropertiesController::class)
    ->actions('applyFilters');

Router::get('removePropertyFilter')
    ->controller(PropertiesController::class)
    ->actions('removePropertyFilter');

Router::post('submitRating')
    ->controller(\App\Controllers\ReviewController::class)
    ->actions('submitRating');

Router::get('details')
    ->controller(DetailsController::class)
    ->actions('show');

Router::post('api/avg-rating')
    ->controller(\App\Controllers\ReviewController::class)
    ->actions('avgRating');


Router::get('reservation')
    ->controller(ReservationController::class)
    ->actions('index');

Router::post('api/save-reservation-data')
    ->controller(ReservationController::class)
    ->actions('saveReservationData');

Router::post('api/reservation-form')
    ->controller(ReservationController::class)
    ->actions('reservationForm');

Router::get('user_reservation')
    ->controller(UserReservationController::class)
    ->actions('index');

Router::get('cancel-reservation')
    ->controller(UserReservationController::class)
    ->actions('cancelReservation');

Router::get('add_property')
    ->controller(AddPropertyStepController::class)
    ->actions('index');

Router::post('add-property-step1')
    ->controller(AddPropertyStepController::class)
    ->actions('store');



Router::get('add-property-3')
    ->controller(AddPropertyStep3Controller::class)
    ->actions('index');


Router::post('add-property-step3')
    ->controller(AddPropertyStep3Controller::class)
    ->actions('store');

Router::get('add-price-images')
    ->controller(AddHousingPriceImagesController::class)
    ->actions('index');


Router::post('add-housing-price-images')
    ->controller(AddHousingPriceImagesController::class)
    ->actions('store');

Router::get('add_amenities')
    ->controller(AddAmenitiesController::class)
    ->actions('index');

Router::post('add-housing-amenities')
    ->controller(AddAmenitiesController::class)
    ->actions('store');
