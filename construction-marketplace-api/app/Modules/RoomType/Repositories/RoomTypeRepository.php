<?php

namespace App\Modules\RoomType\Repositories;

use App\Models\RoomType;
use App\Modules\RoomType\Repositories\Contracts\RoomTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoomTypeRepository implements RoomTypeRepositoryInterface
{
    /**
     * Get all active room types.
     */
    public function getActive(): Collection
    {
        return RoomType::active()->ordered()->get();
    }

    /**
     * Get room types with translations for a specific language.
     */
    public function getWithTranslations(string $languageCode): Collection
    {
        return RoomType::active()
            ->with(['translations' => function ($query) use ($languageCode) {
                $query->where('language_code', $languageCode);
            }])
            ->ordered()
            ->get();
    }

    /**
     * Find a room type by ID.
     */
    public function find(int $id): ?RoomType
    {
        return RoomType::find($id);
    }

    /**
     * Find a room type by code.
     */
    public function findByCode(string $code): ?RoomType
    {
        return RoomType::where('code', $code)->first();
    }

    /**
     * Create a new room type.
     */
    public function create(array $data): RoomType
    {
        return RoomType::create($data);
    }

    /**
     * Update an existing room type.
     */
    public function update(int $id, array $data): RoomType
    {
        $roomType = $this->find($id);

        if (!$roomType) {
            throw new \Exception('Room type not found');
        }

        $roomType->update($data);
        return $roomType;
    }

    /**
     * Delete a room type.
     */
    public function delete(int $id): bool
    {
        $roomType = $this->find($id);

        if (!$roomType) {
            return false;
        }

        return $roomType->delete();
    }

    /**
     * Get room type by ID with translations.
     */
    public function getByIdWithTranslations(int $id): ?RoomType
    {
        return RoomType::with('translations')->find($id);
    }

    /**
     * Get room type by code with translations.
     */
    public function getByCodeWithTranslations(string $code): ?RoomType
    {
        return RoomType::with('translations')->where('code', $code)->first();
    }
}
