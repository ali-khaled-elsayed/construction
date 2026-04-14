<?php

namespace App\Modules\Translation\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CityTranslationRepositoryInterface
{
    /**
     * Find a city translation by its ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Find a city translation by city ID and language code.
     *
     * @param int $cityId
     * @param string $languageCode
     * @return Model|null
     */
    public function findByCityAndLanguage(int $cityId, string $languageCode): ?Model;

    /**
     * Get all translations for a city.
     *
     * @param int $cityId
     * @return Collection
     */
    public function getByCity(int $cityId): Collection;

    /**
     * Get all translations for a language.
     *
     * @param string $languageCode
     * @return Collection
     */
    public function getByLanguage(string $languageCode): Collection;

    /**
     * Get all city translations with city and language relationships.
     *
     * @return Collection
     */
    public function allWithRelationships(): Collection;

    /**
     * Create a new city translation.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing city translation.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a city translation by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Delete all translations for a city.
     *
     * @param int $cityId
     * @return bool
     */
    public function deleteByCity(int $cityId): bool;

    /**
     * Get paginated city translations.
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate(int $perPage = 15);
}
