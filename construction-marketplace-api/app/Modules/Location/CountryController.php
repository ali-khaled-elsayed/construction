<?php

namespace App\Modules\Location;

use App\Http\Controllers\Controller;
use App\Modules\Location\Requests\CreateCountryRequest;
use App\Modules\Location\Requests\UpdateCountryRequest;
use App\Modules\Location\Resources\CountryResource;
use App\Modules\Location\Services\CountryService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(private CountryService $countryService) {}

    /**
     * Display a listing of countries.
     */
    public function index(Request $request)
    {
        $languageCode = $request->get('lang', app()->getLocale());
        $perPage = $request->get('per_page', 15);

        $result = $this->countryService->getAll(['per_page' => $perPage]);

        return successJsonResponse(
            data_get($result, 'data'),
            __('country.success.get_all_countries'),
            data_get($result, 'meta')
        );
    }

    /**
     * Store a newly created country.
     */
    public function store(CreateCountryRequest $request)
    {
        $country = $this->countryService->create($request->validated());
        return successJsonResponse(
            new CountryResource($country),
            __('country.success.create_country'),
            HttpStatusCodeEnum::Created->value
        );
    }

    /**
     * Display the specified country.
     */
    public function show(string $code)
    {
        $country = $this->countryService->getByCode($code);
        return successJsonResponse(
            new CountryResource($country),
            __('country.success.get_country')
        );
    }

    /**
     * Display the specified country with language translation.
     */
    public function showWithLanguage(string $code, string $languageCode)
    {
        $country = $this->countryService->getByCodeWithLanguage($code, $languageCode);
        return successJsonResponse(
            new CountryResource($country),
            __('country.success.get_country_with_language')
        );
    }

    /**
     * Update the specified country.
     */
    public function update(UpdateCountryRequest $request, string $code)
    {
        $country = $this->countryService->update($code, $request->validated());
        return successJsonResponse(
            new CountryResource($country),
            __('country.success.update_country')
        );
    }

    /**
     * Remove the specified country.
     */
    public function destroy(string $code)
    {
        $this->countryService->delete($code);
        return successJsonResponse(
            [],
            __('country.success.delete_country')
        );
    }

    /**
     * Get country name in a specific language.
     */
    public function getName(string $code, string $languageCode)
    {
        $name = $this->countryService->getCountryNameInLanguage($code, $languageCode);

        if (!$name) {
            return errorJsonResponse(
                __('country.errors.country_not_found'),
                HttpStatusCodeEnum::Not_Found->value
            );
        }

        return successJsonResponse(
            ['code' => $code, 'language_code' => $languageCode, 'name' => $name],
            __('country.success.get_country_name')
        );
    }
}
