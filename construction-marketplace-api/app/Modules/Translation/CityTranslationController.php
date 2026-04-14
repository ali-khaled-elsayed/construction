<?php

namespace App\Modules\Translation;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;
use App\Modules\Translation\Requests\CreateCityTranslationRequest;
use App\Modules\Translation\Requests\UpdateCityTranslationRequest;
use App\Modules\Translation\Resources\CityTranslationResource;
use App\Modules\Translation\Services\CityTranslationService;

class CityTranslationController extends Controller
{
    public function __construct(private CityTranslationService $cityTranslationService) {}

    /**
     * Display a listing of city translations.
     */
    public function index()
    {
        $result = $this->cityTranslationService->getAll(request()->all());
        return successJsonResponse(
            data_get($result, 'data'),
            __('city_translation.success.get_all_translations'),
            data_get($result, 'meta')
        );
    }

    /**
     * Store a newly created city translation.
     */
    public function store(CreateCityTranslationRequest $request)
    {
        $translation = $this->cityTranslationService->create($request->validated());
        return successJsonResponse(
            new CityTranslationResource($translation),
            __('city_translation.success.create_translation'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified city translation.
     */
    public function show(int $id)
    {
        $translation = $this->cityTranslationService->getById($id);
        return successJsonResponse(
            new CityTranslationResource($translation),
            __('city_translation.success.get_translation')
        );
    }

    /**
     * Get city translation by city ID and language code.
     */
    public function showByCityAndLanguage(int $cityId, string $languageCode)
    {
        $translation = $this->cityTranslationService->getByCityAndLanguage($cityId, $languageCode);
        return successJsonResponse(
            new CityTranslationResource($translation),
            __('city_translation.success.get_translation')
        );
    }

    /**
     * Get all translations for a city.
     */
    public function getByCity(int $cityId)
    {
        $translations = $this->cityTranslationService->getByCity($cityId);
        return successJsonResponse(
            $translations,
            __('city_translation.success.get_city_translations')
        );
    }

    /**
     * Get all translations for a language.
     */
    public function getByLanguage(string $languageCode)
    {
        $translations = $this->cityTranslationService->getByLanguage($languageCode);
        return successJsonResponse(
            $translations,
            __('city_translation.success.get_language_translations')
        );
    }

    /**
     * Update the specified city translation.
     */
    public function update(UpdateCityTranslationRequest $request, int $id)
    {
        $translation = $this->cityTranslationService->update($id, $request->validated());
        return successJsonResponse(
            new CityTranslationResource($translation),
            __('city_translation.success.update_translation')
        );
    }

    /**
     * Remove the specified city translation.
     */
    public function destroy(int $id)
    {
        $this->cityTranslationService->delete($id);
        return successJsonResponse(
            [],
            __('city_translation.success.delete_translation')
        );
    }

    /**
     * Get city name in a specific language.
     */
    public function getCityName(int $cityId, string $languageCode)
    {
        $name = $this->cityTranslationService->getCityNameInLanguage($cityId, $languageCode);

        if (!$name) {
            return errorJsonResponse(
                __('city_translation.errors.translation_not_found'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            ['city_id' => $cityId, 'language_code' => $languageCode, 'name' => $name],
            __('city_translation.success.get_city_name')
        );
    }

    /**
     * Get cities with translations for a specific language.
     */
    public function getCitiesWithTranslation(string $languageCode)
    {
        $cities = $this->cityTranslationService->getCitiesWithTranslation($languageCode);
        return successJsonResponse(
            $cities,
            __('city_translation.success.get_cities_with_translation')
        );
    }
}
