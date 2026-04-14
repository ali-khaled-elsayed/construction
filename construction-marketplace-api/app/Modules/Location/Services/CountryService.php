<?php

namespace App\Modules\Location\Services;

use App\Models\Country;
use App\Models\CountryTranslation;
use App\Modules\Location\Repositories\CountryRepository;
use App\Modules\Location\Resources\CountryResource;
use App\Modules\Location\Resources\CountryCollection;

class CountryService
{
    public function __construct(private CountryRepository $countryRepository) {}

    /**
     * Get all countries with pagination.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $countries = $this->countryRepository->paginate($perPage);

        return [
            'data' => new CountryCollection($countries),
            'meta' => [
                'current_page' => $countries->currentPage(),
                'last_page' => $countries->lastPage(),
                'per_page' => $countries->perPage(),
                'total' => $countries->total(),
            ],
        ];
    }

    /**
     * Get a country by code.
     */
    public function getByCode(string $code): CountryResource
    {
        $country = $this->countryRepository->findByCode($code);

        if (!$country) {
            throw new \Exception('Country not found');
        }

        return new CountryResource($country->load('translations'));
    }

    /**
     * Create a new country with translations.
     */
    public function create(array $data): CountryResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Create the country
        $country = $this->countryRepository->create($data);

        // Create translations if provided
        if (!empty($translations)) {
            $this->createTranslations($country, $translations);
        }

        return new CountryResource($country->fresh(['translations']));
    }

    /**
     * Update an existing country with translations.
     */
    public function update(string $code, array $data): CountryResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Update the country
        $country = $this->countryRepository->update($code, $data);

        // Update translations if provided
        if (!empty($translations)) {
            $this->updateTranslations($country, $translations);
        }

        return new CountryResource($country->fresh(['translations']));
    }

    /**
     * Delete a country.
     */
    public function delete(string $code): bool
    {
        return $this->countryRepository->delete($code);
    }

    /**
     * Get country name in a specific language.
     */
    public function getCountryNameInLanguage(string $code, string $languageCode): ?string
    {
        $country = $this->countryRepository->findByCode($code);

        if (!$country) {
            return null;
        }

        // Check for translation
        $translation = $country->translations()->where('language_code', $languageCode)->first();

        if ($translation) {
            return $translation->name;
        }

        // Fallback to original name
        return $country->name;
    }

    /**
     * Get a country by code with language translation loaded.
     */
    public function getByCodeWithLanguage(string $code, string $languageCode): CountryResource
    {
        $country = $this->countryRepository->findByCode($code);

        if (!$country) {
            throw new \Exception('Country not found');
        }

        // Load translation for the specified language
        $country->load(['translations' => function ($query) use ($languageCode) {
            $query->where('language_code', $languageCode);
        }]);

        return new CountryResource($country);
    }

    /**
     * Create translations for a country.
     */
    private function createTranslations(Country $country, array $translations): void
    {
        foreach ($translations as $translationData) {
            CountryTranslation::create([
                'country_code' => $country->code,
                'language_code' => $translationData['language_code'],
                'name' => $translationData['name'],
            ]);
        }
    }

    /**
     * Update translations for a country.
     */
    private function updateTranslations(Country $country, array $translations): void
    {
        foreach ($translations as $translationData) {
            // Check if translation exists
            $translation = CountryTranslation::where('country_code', $country->code)
                ->where('language_code', $translationData['language_code'])
                ->first();

            if ($translation) {
                // Update existing translation
                $translation->update([
                    'name' => $translationData['name'] ?? $translation->name,
                ]);
            } else {
                // Create new translation
                CountryTranslation::create([
                    'country_code' => $country->code,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                ]);
            }
        }
    }
}
