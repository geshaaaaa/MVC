<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Amenities;
use App\Models\Housing;
use App\Models\HousingAmenities;
use App\Session\Session;
use Core\Controller;
use Core\Redirect\Redirect;
use Core\View\View;


class PropertiesController extends Controller
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function index()
    {
        View::page('properties');
        return $this->response(Status::OK, ['message' => 'Properties page loaded']);
    }

    public function applyFilters()
    {
        $fields = $_GET;
        $types = $fields["type"] ?? [];
        $amenities = $fields["amenity"] ?? [];
        $minPrice = isset($fields["min-price"]) && $fields["min-price"] !== '' ? (float) $fields["min-price"] : null;
        $maxPrice = isset($fields["max-price"]) && $fields["max-price"] !== '' ? (float) $fields["max-price"] : null;

        // Сохранение примененных фильтров в сессию
        $this->session->set('applied_filters', compact('types', 'amenities', 'minPrice', 'maxPrice'));

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

        $this->session->set('all_properties', $filteredResults);

        Redirect::to("/properties");
        exit();
    }

    private function applyFiltersFromSession()
    {
        $filters = $this->session->get('applied_filters', []);

        $types = $filters['types'] ?? [];
        $amenities = $filters['amenities'] ?? [];
        $minPrice = $filters['minPrice'] ?? null;
        $maxPrice = $filters['maxPrice'] ?? null;

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

        $this->session->set('all_properties', $filteredResults);

    }

    public function removePropertyFilter()
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

        $this->applyFiltersFromSession();

        Redirect::to('/properties');
        exit();

    }
}