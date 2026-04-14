<?php

namespace App\Modules\Translation\Services;

use App\Models\CityTranslation;
use App\Modules\Translation\Repositories\Contracts\CityTranslationRepositoryInterface;
use App\Modules\Translation\Resources\CityTranslationResource;
use App\Modules\Translation\Resources\CityTranslationCollection;

class CityTranslationService
{
    public function __construct(private CityTranslationRepositoryInterface $cityTranslationRepository) {}

    /**
     * Get all city translations with pagination.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $translations = $this->cityTranslationRepository->paginate($perPage);

        return [
            'data' => new CityTranslationCollection($translations),
            'meta' => [
                'current_page' => $translations->currentPage(),
                'last_page' => $translations->lastPage(),
                'per_page' => $translations->perPage(),
                'total' => $translations->total(),
            ],
        ];
    }

    /**
     * Get a city translation by ID.
     */
    public function getById(int $id): CityTranslationResource
    {
        $translation = $this->cityTranslationRepository->find($id);

        if (!$translation) {
            throw new \Exception('City translation not found');
        }

        return new CityTranslationResource($translation);
    }

    /**
     * Get city translation by city ID and language code.
     */
    public function getByCityAndLanguage(int $cityId, string $languageCode): CityTranslationResource
    {
        $translation = $this->cityTranslationRepository->findByCityAndLanguage($cityId, $languageCode);

        if (!$translation) {
            throw new \Exception('City translation not found for this language');
        }

        return new CityTranslationResource($translation);
    }

    /**
     * Get all translations for a city.
     */
    public function getByCity(int $cityId): CityTranslationCollection
    {
        $translations = $this->cityTranslationRepository->getByCity($cityId);
        return new CityTranslationCollection($translations);
    }

    /**
     * Get all translations for a language.
     */
    public function getByLanguage(string $languageCode): CityTranslationCollection
    {
        $translations = $this->cityTranslationRepository->getByLanguage($languageCode);
        return new CityTranslationCollection($translations);
    }

    /**
     * Create a new city translation.
     */
    public function create(array $data): CityTranslationResource
    {
        // Check if translation already exists
        $existing = $this->cityTranslationRepository->findByCityAndLanguage(
            $data['city_id'],
            $data['language_code']
        );

        if ($existing) {
            throw new \Exception('Translation already exists for this city and language');
        }

        $translation = $this->cityTranslationRepository->create($data);
        return new CityTranslationResource($translation->fresh(['city', 'language']));
    }

    /**
     * Update an existing city translation.
     */
    public function update(int $id, array $data): CityTranslationResource
    {
        $translation = $this->cityTranslationRepository->find($id);

        if (!$translation) {
            throw new \Exception('City translation not found');
        }

        $translation->update($data);
        return new CityTranslationResource($translation->fresh(['city', 'language']));
    }

    /**
     * Delete a city translation.
     */
    public function delete(int $id): bool
    {
        return $this->cityTranslationRepository->delete($id);
    }

    /**
     * Get city name in a specific language (with fallback).
     */
    public function getCityNameInLanguage(int $cityId, string $languageCode): ?string
    {
        $translation = $this->cityTranslationRepository->findByCityAndLanguage($cityId, $languageCode);

        if ($translation) {
            return $translation->name;
        }

        // Fallback to default language
        $defaultTranslation = $this->cityTranslationRepository->findByCityAndLanguage(
            $cityId,
            config('app.fallback_locale', 'en')
        );

        if ($defaultTranslation) {
            return $defaultTranslation->name;
        }

        // Fallback to city's original name
        $city = app(\App\Repositories\Eloquent\CityRepository::class)->find($cityId);
        return $city ? $city->name : null;
    }

    /**
     * Get cities with translations for a specific language.
     */
    public function getCitiesWithTranslation(string $languageCode): array
    {
        $translations = $this->cityTranslationRepository->getByLanguage($languageCode);

        return $translations->map(function ($translation) {
            return [
                'id' => $translation->city_id,
                'original_name' => $translation->city->name ?? null,
                'translated_name' => $translation->name,
                'language_code' => $translation->language_code,
            ];
        })->toArray();
    }
}
