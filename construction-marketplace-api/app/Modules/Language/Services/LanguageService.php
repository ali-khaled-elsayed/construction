<?php

namespace App\Modules\Language\Services;

use App\Modules\Job\Requests\ListAllJobRequestsRequest;
use App\Modules\Language\Repositories\LanguageRepository;
use App\Modules\Language\Resources\LanguageCollection;
use App\Modules\Language\Resources\LanguageResource;

class LanguageService
{
    public function __construct(private LanguageRepository $languageRepository) {}

    /**
     * Get all languages with pagination and filtering.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $languages = $this->languageRepository->paginate($perPage);

        return [
            'data' => new LanguageCollection($languages),
            'meta' => [
                'current_page' => $languages->currentPage(),
                'last_page' => $languages->lastPage(),
                'per_page' => $languages->perPage(),
                'total' => $languages->total(),
            ],
        ];
    }

    public function listAllLanguages(array $queryParameters)
    {
        $listAllLanguages = (new ListAllJobRequestsRequest)->constructQueryCriteria($queryParameters);
        $Languages = $this->languageRepository->findAllBy($listAllLanguages);
        return [
            'data' => $Languages['data'],
            'count' => $Languages['count']
        ];
    }

    /**
     * Get a single language by ID.
     */
    public function getById(int $id): LanguageResource
    {
        $language = $this->languageRepository->find($id);

        if (!$language) {
            throw new \Exception('Language not found');
        }

        return new LanguageResource($language);
    }

    /**
     * Get a language by its code.
     */
    public function getByCode(string $code): LanguageResource
    {
        $language = $this->languageRepository->findByCode($code);

        if (!$language) {
            throw new \Exception('Language not found');
        }

        return new LanguageResource($language);
    }

    /**
     * Create a new language.
     */
    public function create(array $data): LanguageResource
    {
        // If setting as default, unset other defaults
        if (isset($data['is_default']) && $data['is_default']) {
            $this->unsetAllDefaults();
        }

        $language = $this->languageRepository->create($data);
        return $language;
    }

    /**
     * Update an existing language.
     */
    public function update(int $id, array $data): LanguageResource
    {
        $language = $this->languageRepository->find($id);

        if (!$language) {
            throw new \Exception('Language not found');
        }

        // Check if code already exists (excluding current language)
        if (isset($data['code']) && $data['code'] !== $language->code) {
            if ($this->languageRepository->findByCode($data['code'])) {
                throw new \Exception('Language code already exists');
            }
        }

        // If setting as default, unset other defaults
        if (isset($data['is_default']) && $data['is_default'] && $language->is_default != $data['is_default']) {
            $this->unsetAllDefaults();
        }

        $language->update($data);
        return new LanguageResource($language->fresh());
    }

    /**
     * Delete a language.
     */
    public function delete(int $id): bool
    {
        $language = $this->languageRepository->find($id);

        if (!$language) {
            throw new \Exception('Language not found');
        }

        // Prevent deletion of the default language
        if ($language->is_default) {
            throw new \Exception('Cannot delete the default language');
        }

        return $this->languageRepository->delete($id);
    }

    /**
     * Get the default language.
     */
    public function getDefault(): ?LanguageResource
    {
        $language = $this->languageRepository->getDefault();

        return $language ? new LanguageResource($language) : null;
    }

    /**
     * Search languages by name.
     */
    public function search(string $searchTerm): LanguageCollection
    {
        $languages = $this->languageRepository->search($searchTerm);
        return new LanguageCollection($languages);
    }

    /**
     * Unset all default flags.
     */
    private function unsetAllDefaults(): void
    {
        $this->languageRepository->all()->each(function ($language) {
            if ($language->is_default) {
                $language->update(['is_default' => false]);
            }
        });
    }
}
