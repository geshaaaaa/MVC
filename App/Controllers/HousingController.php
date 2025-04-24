<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Amenities;
use App\Models\Housing;
use App\Models\HousingAmenities;
use App\Models\Reservations;
use App\Session\Session;
use App\Validators\HousingValidation\HousingValidator;
use App\Validators\ReservationValidation\ReservationValidator;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;
use App\Controllers\ReservationController;

class HousingController extends Controller
{
    private Session $session;
    private ReservationController $reservationController;

    public $availableFilters;

    public function __construct()
    {
        $this->session = new Session();
        $this->reservationController = new ReservationController();
    }

    public function index()
    {


        View::page('search');

        return $this->response(Status::OK, ['message' => 'Search page loaded']);
    }

    public function searchHousing()
    {
        $fields = $_GET;



        $location = $fields['location'];
        (int)$guests = $fields['guests_capacity'];
        $checkIn = $fields['check_in'] ? date('Y-m-d', strtotime($fields['check_in'])) : null;
        $checkOut = $fields['check_out'] ? date('Y-m-d', strtotime($fields['check_out'])) : null;


        $reservedHousingIds =  $this->reservationController->getReservedHousingIds($checkIn,$checkOut);

            if (HousingValidator::validate(['location' => $location, 'guests_capacity' => $guests])) {

                $query = Housing::select()
                    ->where('location', CommandsSQL::EQUAL, $location)
                    ->and('guests_capacity_max', CommandsSQL::GREATER_EQUAL, $guests);

                if (!empty($reservedHousingIds)) {
                    $query->whereNotIn('id', $reservedHousingIds);
                }


                $results = $query->get();


                $this->session->set('search_results', $results);

                Redirect::to("/search");
                exit;
            }


        $this->session->set('errors', HousingValidator::getErrors());
        Redirect::to("/");
        exit;

    }

    public function housingFilter()
    {
        $fields = $_GET;



        // Получаем фильтры
        $location = $fields['location'] ?? null;
        $guests = $fields['guests_capacity'] ?? null;
        $types = $fields["type"] ?? [];
        $amenities = $fields["amenity"] ?? [];
        $minPrice = $fields["min-price"] ?? null;
        $maxPrice = $fields["max-price"] ?? null;

        // Преобразуем цены в float
        $minPrice = ($minPrice !== null && $minPrice !== '') ? (float) $minPrice : null;
        $maxPrice = ($maxPrice !== null && $maxPrice !== '') ? (float) $maxPrice : null;

        if (empty($types) && empty($amenities) && empty($minPrice) && empty($maxPrice)) {
            // Возвращаем все записи без фильтров
            $searchResults = Housing::select()->get();
            $this->session->set('search_results', $searchResults);
            Redirect::to("/search");
            exit;
        }

        // Получаем результаты поиска
        $searchResults = $this->session->get('search_results', []);

        $this->appliedFilters($types, $amenities, $minPrice, $maxPrice);

        $filteredResults = [];

        foreach ($searchResults as $housing) {
            $query = Housing::select()->where("id", CommandsSQL::EQUAL, $housing->id);

            // Фильтрация по типу
            if (!empty($types)) {
                $query = $query->and("type", CommandsSQL::IN, $types);
            }

            // Фильтрация по локации
            if ($location) {
                $query = $query->and("location", CommandsSQL::EQUAL, $location);
            }

            // Фильтрация по количеству гостей
            if ($guests) {
                $query = $query->and("guests_capacity_max", CommandsSQL::GREATER_EQUAL, (int)$guests);
            }

            // Фильтрация по минимальной цене
            if ($minPrice !== null) {
                $query = $query->and("price", CommandsSQL::GREATER_EQUAL, $minPrice);
            }

            // Фильтрация по максимальной цене
            if ($maxPrice !== null) {
                $query = $query->and("price", CommandsSQL::LESS_EQUAL, $maxPrice);
            }

            // Фильтрация по удобствам
            if (!empty($amenities)) {
                $amenityNames = [];
                foreach ($amenities as $amenityName) {
                    $amenity = Amenities::findBy('name', $amenityName);
                    if ($amenity) {
                        $amenityNames[] = $amenity->name;
                    }
                }

                if (!empty($amenityNames)) {
                    $query->whereExists(function ($subQuery) use ($amenityNames, $housing) {
                        $subQuery = HousingAmenities::select(['housing_amenities.housing_id'])
                            ->join('amenities', [['left' => 'amenities.id', 'operator' => '=', 'right' => 'housing_amenities.amenity_id']])
                            ->where("housing_id", CommandsSQL::EQUAL, $housing->id)
                            ->and("amenities.name", CommandsSQL::IN, $amenityNames);

                        return count($subQuery->fetchAssoc()) > 0; // Используем fetchAssoc()
                    });
                }
            }

            if ($query->hasAnyResults()) {
                $filteredResults[] = $housing;
            }
        }

        $this->session->set('search_results', $filteredResults);

        Redirect::to("/search");
        exit;
    }


    // Удаление фильтров
    public function removeFilter()
    {
        if (!empty($_GET['filter'])) {
            $filterKey = $_GET['filter'];
            $filterValue = $_GET['value'] ?? null;

            $appliedFilters = $this->session->get('applied_filters', []);
            if (isset($appliedFilters[$filterKey])) {
                if (is_array($appliedFilters[$filterKey])) {
                    $appliedFilters[$filterKey] = array_diff($appliedFilters[$filterKey], [$filterValue]);

                    if (empty($appliedFilters[$filterKey])) {
                        unset($appliedFilters[$filterKey]);
                    }
                } else {
                    unset($appliedFilters[$filterKey]);
                }

                $this->session->set('applied_filters', $appliedFilters);
            }
        }

        $this->housingAppliedFilter();

        Redirect::to("/search");
        exit();
    }


    public function housingAppliedFilter()
    {
        $fields = $this->session->get('applied_filters', []);

        $types = $fields["type"] ?? [];
        $amenities = $fields["amenity"] ?? [];
        $minPrice = $fields["min-price"] ?? null;
        $maxPrice = $fields["max-price"] ?? null;

        $minPrice = ($minPrice !== null && $minPrice !== '') ? (float) $minPrice : null;
        $maxPrice = ($maxPrice !== null && $maxPrice !== '') ? (float) $maxPrice : null;



        $query = Housing::select();

        if (!empty($types)) {
            $query->where("type", CommandsSQL::IN, $types);
        }

        if ($minPrice !== null) {
            $query->where("price", CommandsSQL::GREATER_EQUAL, $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where("price", CommandsSQL::LESS_EQUAL, $maxPrice);
        }

        $filteredResults = $query->get();

        if (!empty($amenities)) {
            $filteredResults = array_filter($filteredResults, function ($housing) use ($amenities) {
                foreach ($amenities as $amenityName) {
                    $amenity = Amenities::findBy('name', $amenityName);
                    if (!$amenity) {
                        return false;
                    }

                    $exists = HousingAmenities::select(['housing_amenities.housing_id'])
                        ->join('amenities', [['left' => 'amenities.id', 'operator' => '=', 'right' => 'housing_amenities.amenity_id']])
                        ->where("housing_id", CommandsSQL::EQUAL, $housing->id)
                        ->and("amenities.name", CommandsSQL::EQUAL, $amenity->name)
                        ->hasAnyResults();

                    if (!$exists) {
                        return false;
                    }
                }
                return true;
            });
        }

        $this->session->set('search_results', $filteredResults);
    }

    public function appliedFilters($types, $amenities, $minPrice, $maxPrice) : void
    {
        $appliedFilters = [
            'type' => $types,
            'amenity' => $amenities,
            'min-price' => $minPrice,
            'max-price' => $maxPrice
        ];

        $this->session->set('applied_filters', $appliedFilters);
    }

    public function getLatestListings()
    {
        $latestListings = Housing::select()
            ->orderBy(['created_at' => 'DESC'])
            ->get();

        // Ограничиваем массив до 4 записей, если нет метода limit()
        $latestListings = array_slice($latestListings, 0, 4);

        $this->session->set('latest_listings', $latestListings);


    }





}
