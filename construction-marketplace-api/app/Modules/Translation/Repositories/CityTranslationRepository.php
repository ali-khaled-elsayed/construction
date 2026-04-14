<?php

namespace App\Modules\Translation\Repositories;

use App\Models\CityTranslation;
use App\Modules\Translation\Repositories\Contracts\CityTranslationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CityTranslationRepository implements CityTranslationRepositoryInterface
{
    public function __construct(private CityTranslation $model) {}

    /**
     * Find a city translation by its ID.
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a city translation by city ID and language code.
     */
    public function findByCityAndLanguage(int $cityId, string $languageCode): ?Model
    {
        return $this->model
            ->where('city_id', $cityId)
            ->where('language_code', $languageCode)
            ->first();
    }

    /**
     * Get all translations for a city.
     */
    public function getByCity(int $cityId): Collection
    {
        return $this->model->where('city_id', $cityId)->get();
    }

    /**
     * Get all translations for a language.
     */
    public function getByLanguage(string $languageCode): Collection
    {
        return $this->model->where('language_code', $languageCode)->get();
    }

    /**
     * Get all city translations with city and language relationships.
     */
    public function allWithRelationships(): Collection
    {
        return $this->model->with(['city', 'language'])->get();
    }

    /**
     * Create a new city translation.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing city translation.
     */
    public function update(int $id, array $data): Model
    {
        $translation = $this->find($id);
        $translation->update($data);
        return $translation->fresh();
    }

    /**
     * Delete a city translation by its ID.
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    /**
     * Delete all translations for a city.
     */
    public function deleteByCity(int $cityId): bool
    {
        return $this->model->where('city_id', $cityId)->delete() > 0;
    }

    /**
     * Get paginated city translations.
     */
    public function paginate(int $perPage = 15)
    {
        return $this->model->with(['city', 'language'])->paginate($perPage);
    }
}
