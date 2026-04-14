<?php

namespace App\Modules\Location\Services;

use App\Models\City;
use App\Models\CityTranslation;
use App\Modules\Location\Repositories\CityRepository;
use App\Modules\Location\Resources\CityResource;
use App\Modules\Location\Resources\CityCollection;

class CityService
{
    public function __construct(private CityRepository $cityRepository) {}

    /**
     * Get all cities with pagination.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $cities = $this->cityRepository->paginate($perPage);

        return [
            'data' => new CityCollection($cities),
            'meta' => [
                'current_page' => $cities->currentPage(),
                'last_page' => $cities->lastPage(),
                'per_page' => $cities->perPage(),
                'total' => $cities->total(),
            ],
        ];
    }

    /**
     * Get a city by ID.
     */
    public function getById(int $id): CityResource
    {
        $city = $this->cityRepository->find($id);

        if (!$city) {
            throw new \Exception('City not found');
        }

        return new CityResource($city->load('translations'));
    }

    /**
     * Create a new city with translations.
     */
    public function create(array $data): CityResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Create the city
        $city = $this->cityRepository->create($data);

        // Create translations if provided
        if (!empty($translations)) {
            $this->createTranslations($city, $translations);
        }

        return new CityResource($city->fresh(['translations']));
    }

    /**
     * Update an existing city with translations.
     */
    public function update(int $id, array $data): CityResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Update the city
        $city = $this->cityRepository->update($id, $data);

        // Update translations if provided
        if (!empty($translations)) {
            $this->updateTranslations($city, $translations);
        }

        return new CityResource($city->fresh(['translations']));
    }

    /**
     * Delete a city.
     */
    public function delete(int $id): bool
    {
        return $this->cityRepository->delete($id);
    }

    /**
     * Get cities by country with optional language translation.
     */
    public function getByCountry(string $countryCode, string $languageCode = null): CityCollection
    {
        $cities = $this->cityRepository->getByCountry($countryCode);

        if ($languageCode) {
            // Load translations for the specified language
            $cities->load(['translations' => function ($query) use ($languageCode) {
                $query->where('language_code', $languageCode);
            }]);
        }

        return new CityCollection($cities);
    }

    /**
     * Get city name in a specific language.
     */
    public function getCityNameInLanguage(int $cityId, string $languageCode): ?string
    {
        $city = $this->cityRepository->find($cityId);

        if (!$city) {
            return null;
        }

        // Check for translation
        $translation = $city->translations()->where('language_code', $languageCode)->first();

        if ($translation) {
            return $translation->name;
        }

        // Fallback to original name
        return $city->name;
    }

    /**
     * Get a city by ID with language translation loaded.
     */
    public function getByIdWithLanguage(int $id, string $languageCode)
    {
        $city = $this->cityRepository->find($id);

        if (!$city) {
            throw new \Exception('City not found');
        }

        // Load translation for the specified language
        $city->load(['translations' => function ($query) use ($languageCode) {
            $query->where('language_code', $languageCode);
        }]);

        return $city;
    }

    /**
     * Create translations for a city.
     */
    private function createTranslations(City $city, array $translations): void
    {
        foreach ($translations as $translationData) {
            CityTranslation::create([
                'city_id' => $city->id,
                'language_code' => $translationData['language_code'],
                'name' => $translationData['name'],
            ]);
        }
    }

    /**
     * Update translations for a city.
     */
    private function updateTranslations(City $city, array $translations): void
    {
        foreach ($translations as $translationData) {
            // Check if translation exists
            $translation = CityTranslation::where('city_id', $city->id)
                ->where('language_code', $translationData['language_code'])
                ->first();

            if ($translation) {
                // Update existing translation
                $translation->update([
                    'name' => $translationData['name'] ?? $translation->name,
                ]);
            } else {
                // Create new translation
                CityTranslation::create([
                    'city_id' => $city->id,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                ]);
            }
        }
    }
}
