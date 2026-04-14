<?php

namespace App\Modules\RoomType\Services;

use App\Models\RoomType;
use App\Models\RoomTypeTranslation;
use App\Modules\RoomType\Repositories\RoomTypeRepository;
use App\Modules\RoomType\Resources\RoomTypeResource;
use App\Modules\RoomType\Resources\RoomTypeCollection;
use Illuminate\Database\Eloquent\Collection;

class RoomTypeService
{
    public function __construct(private RoomTypeRepository $roomTypeRepository) {}

    /**
     * Get all room types with pagination.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $languageCode = $queryParameters['lang'] ?? app()->getLocale();

        if ($languageCode) {
            $roomTypes = $this->roomTypeRepository->getWithTranslations($languageCode);
        } else {
            $roomTypes = $this->roomTypeRepository->getActive();
        }

        return [
            'data' => new RoomTypeCollection($roomTypes),
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => $roomTypes->count(),
            ],
        ];
    }

    /**
     * Get a room type by ID.
     */
    public function getById(int $id): RoomTypeResource
    {
        $roomType = $this->roomTypeRepository->getByIdWithTranslations($id);

        if (!$roomType) {
            throw new \Exception('Room type not found');
        }

        return new RoomTypeResource($roomType);
    }

    /**
     * Get a room type by code.
     */
    public function getByCode(string $code): RoomTypeResource
    {
        $roomType = $this->roomTypeRepository->getByCodeWithTranslations($code);

        if (!$roomType) {
            throw new \Exception('Room type not found');
        }

        return new RoomTypeResource($roomType);
    }

    /**
     * Create a new room type with translations.
     */
    public function create(array $data): RoomTypeResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Create the room type
        $roomType = $this->roomTypeRepository->create($data);

        // Create translations if provided
        if (!empty($translations)) {
            $this->createTranslations($roomType, $translations);
        }

        return new RoomTypeResource($roomType->fresh('translations'));
    }

    /**
     * Update an existing room type with translations.
     */
    public function update(int $id, array $data): RoomTypeResource
    {
        // Extract translations if present
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Update the room type
        $roomType = $this->roomTypeRepository->update($id, $data);

        // Update translations if provided
        if (!empty($translations)) {
            $this->updateTranslations($roomType, $translations);
        }

        return new RoomTypeResource($roomType->fresh('translations'));
    }

    /**
     * Delete a room type.
     */
    public function delete(int $id): bool
    {
        return $this->roomTypeRepository->delete($id);
    }

    /**
     * Get room type name in a specific language.
     */
    public function getRoomTypeNameInLanguage(int $id, string $languageCode): ?string
    {
        $roomType = $this->roomTypeRepository->find($id);

        if (!$roomType) {
            return null;
        }

        // Check for translation
        $translation = $roomType->translations()->where('language_code', $languageCode)->first();

        if ($translation) {
            return $translation->name;
        }

        // Fallback to original name
        return $roomType->name;
    }

    /**
     * Get room type by code with language translation.
     */
    public function getByCodeWithLanguage(string $code, string $languageCode): RoomTypeResource
    {
        $roomType = $this->roomTypeRepository->getByCodeWithTranslations($code);

        if (!$roomType) {
            throw new \Exception('Room type not found');
        }

        // Load translation for the specified language
        $roomType->load(['translations' => function ($query) use ($languageCode) {
            $query->where('language_code', $languageCode);
        }]);

        return new RoomTypeResource($roomType);
    }

    /**
     * Create translations for a room type.
     */
    private function createTranslations(RoomType $roomType, array $translations): void
    {
        foreach ($translations as $translationData) {
            RoomTypeTranslation::create([
                'room_type_id' => $roomType->id,
                'language_code' => $translationData['language_code'],
                'name' => $translationData['name'],
                'description' => $translationData['description'] ?? null,
            ]);
        }
    }

    /**
     * Update translations for a room type.
     */
    private function updateTranslations(RoomType $roomType, array $translations): void
    {
        foreach ($translations as $translationData) {
            // Check if translation exists
            $translation = RoomTypeTranslation::where('room_type_id', $roomType->id)
                ->where('language_code', $translationData['language_code'])
                ->first();

            if ($translation) {
                // Update existing translation
                $translation->update([
                    'name' => $translationData['name'] ?? $translation->name,
                    'description' => $translationData['description'] ?? $translation->description,
                ]);
            } else {
                // Create new translation
                RoomTypeTranslation::create([
                    'room_type_id' => $roomType->id,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }
        }
    }
}
