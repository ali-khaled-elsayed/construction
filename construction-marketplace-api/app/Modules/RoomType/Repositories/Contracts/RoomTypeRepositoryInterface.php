<?php

namespace App\Modules\RoomType\Repositories\Contracts;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoomTypeRepositoryInterface
{
    /**
     * Get all active room types.
     */
    public function getActive(): Collection;

    /**
     * Get room types with translations for a specific language.
     */
    public function getWithTranslations(string $languageCode): Collection;

    /**
     * Find a room type by ID.
     */
    public function find(int $id): ?RoomType;

    /**
     * Find a room type by code.
     */
    public function findByCode(string $code): ?RoomType;

    /**
     * Create a new room type.
     */
    public function create(array $data): RoomType;

    /**
     * Update an existing room type.
     */
    public function update(int $id, array $data): RoomType;

    /**
     * Delete a room type.
     */
    public function delete(int $id): bool;

    /**
     * Get room type by ID with translations.
     */
    public function getByIdWithTranslations(int $id): ?RoomType;

    /**
     * Get room type by code with translations.
     */
    public function getByCodeWithTranslations(string $code): ?RoomType;
}
