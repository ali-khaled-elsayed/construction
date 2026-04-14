<?php

namespace App\Modules\Location;

use App\Http\Controllers\Controller;
use App\Modules\Location\Requests\CreateCityRequest;
use App\Modules\Location\Requests\UpdateCityRequest;
use App\Modules\Location\Resources\CityResource;
use App\Modules\Location\Services\CityService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(private CityService $cityService) {}

    /**
     * Display a listing of cities.
     */
    public function index(Request $request)
    {
        $languageCode = $request->get('lang', app()->getLocale());
        $countryCode = $request->get('country');
        $perPage = $request->get('per_page', 15);

        if ($countryCode) {
            $result = $this->cityService->getByCountry($countryCode, $languageCode);
        } else {
            $result = $this->cityService->getAll(['per_page' => $perPage]);
        }

        return successJsonResponse(
            data_get($result, 'data'),
            __('city.success.get_all_cities'),
            data_get($result, 'meta')
        );
    }

    /**
     * Store a newly created city.
     */
    public function store(CreateCityRequest $request)
    {
        $city = $this->cityService->create($request->validated());
        return successJsonResponse(
            new CityResource($city),
            __('city.success.create_city'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified city.
     */
    public function show(int $id)
    {
        $city = $this->cityService->getById($id);
        return successJsonResponse(
            new CityResource($city),
            __('city.success.get_city')
        );
    }

    /**
     * Update the specified city.
     */
    public function update(UpdateCityRequest $request, int $id)
    {
        $city = $this->cityService->update($id, $request->validated());
        return successJsonResponse(
            new CityResource($city),
            __('city.success.update_city')
        );
    }

    /**
     * Remove the specified city.
     */
    public function destroy(int $id)
    {
        $this->cityService->delete($id);
        return successJsonResponse(
            [],
            __('city.success.delete_city')
        );
    }

    /**
     * Get city name in a specific language.
     */
    public function getName(int $id, string $languageCode)
    {
        $name = $this->cityService->getCityNameInLanguage($id, $languageCode);

        if (!$name) {
            return errorJsonResponse(
                __('city.errors.city_not_found'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            ['id' => $id, 'language_code' => $languageCode, 'name' => $name],
            __('city.success.get_city_name')
        );
    }

    /**
     * Get all cities by country with language translation.
     */
    public function getByCountryAndLanguage(string $countryCode, string $languageCode)
    {
        $cities = $this->cityService->getByCountry($countryCode, $languageCode);
        return successJsonResponse(
            $cities,
            __('city.success.get_cities_by_country_with_language')
        );
    }

    /**
     * Get a city with language translation.
     */
    public function showWithLanguage(int $id, string $languageCode)
    {
        $city = $this->cityService->getByIdWithLanguage($id, $languageCode);
        return successJsonResponse(
            new CityResource($city),
            __('city.success.get_city_with_language')
        );
    }
}
