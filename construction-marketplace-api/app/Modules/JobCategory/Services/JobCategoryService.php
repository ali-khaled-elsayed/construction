<?php

namespace App\Modules\JobCategory\Services;

use App\Models\JobCategory;
use App\Models\JobCategoryTranslation;
use App\Models\JobAttribute;
use App\Models\JobAttributeTranslation;
use App\Models\JobAttributeOption;
use App\Models\JobAttributeValue;
use App\Modules\JobCategory\Repositories\JobCategoryRepository;
use App\Modules\JobCategory\Resources\JobCategoryResource;
use App\Modules\JobCategory\Resources\JobCategoryCollection;
use Illuminate\Database\Eloquent\Collection;

class JobCategoryService
{
    public function __construct(private JobCategoryRepository $jobCategoryRepository) {}

    /**
     * Get all job categories with pagination.
     */
    public function getAll(array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $languageCode = $queryParameters['lang'] ?? app()->getLocale();

        if ($languageCode) {
            $jobCategories = $this->jobCategoryRepository->getWithTranslations($languageCode);
        } else {
            $jobCategories = $this->jobCategoryRepository->getActive();
        }

        return [
            'data' => new JobCategoryCollection($jobCategories),
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => $jobCategories->count(),
            ],
        ];
    }

    /**
     * Get a job category by ID.
     */
    public function getById(int $id): JobCategoryResource
    {
        $jobCategory = $this->jobCategoryRepository->getByIdWithAttributes($id);

        if (!$jobCategory) {
            throw new \Exception('Job category not found');
        }

        return new JobCategoryResource($jobCategory);
    }

    /**
     * Get a job category by code.
     */
    public function getByCode(string $code): JobCategoryResource
    {
        $jobCategory = $this->jobCategoryRepository->getByCodeWithAttributes($code);

        if (!$jobCategory) {
            throw new \Exception('Job category not found');
        }

        return new JobCategoryResource($jobCategory);
    }

    /**
     * Create a new job category with translations and attributes.
     */
    public function create(array $data): JobCategoryResource
    {
        // Extract translations and attributes if present
        $translations = $data['translations'] ?? [];
        $attributes = $data['attributes'] ?? [];
        unset($data['translations'], $data['attributes']);

        // Create the job category
        $jobCategory = $this->jobCategoryRepository->create($data);

        // Create translations if provided
        if (!empty($translations)) {
            $this->createTranslations($jobCategory, $translations);
        }

        // Create attributes if provided
        if (!empty($attributes)) {
            $this->createAttributes($jobCategory, $attributes);
        }

        return new JobCategoryResource($jobCategory->fresh(['translations', 'attributes']));
    }

    /**
     * Update an existing job category with translations and attributes.
     */
    public function update(int $id, array $data): JobCategoryResource
    {
        // Extract translations and attributes if present
        $translations = $data['translations'] ?? [];
        $attributes = $data['attributes'] ?? [];
        unset($data['translations'], $data['attributes']);

        // Update the job category
        $jobCategory = $this->jobCategoryRepository->update($id, $data);

        // Update translations if provided
        if (!empty($translations)) {
            $this->updateTranslations($jobCategory, $translations);
        }

        // Update attributes if provided
        if (!empty($attributes)) {
            $this->updateAttributes($jobCategory, $attributes);
        }

        return new JobCategoryResource($jobCategory->fresh(['translations', 'attributes']));
    }

    /**
     * Delete a job category.
     */
    public function delete(int $id): bool
    {
        return $this->jobCategoryRepository->delete($id);
    }

    /**
     * Get job category name in a specific language.
     */
    public function getJobCategoryNameInLanguage(int $id, string $languageCode): ?string
    {
        $jobCategory = $this->jobCategoryRepository->find($id);

        if (!$jobCategory) {
            return null;
        }

        // Check for translation
        $translation = $jobCategory->translations()->where('language_code', $languageCode)->first();

        if ($translation) {
            return $translation->name;
        }

        // Fallback to original name
        return $jobCategory->name;
    }

    /**
     * Get job category by code with language translation.
     */
    public function getByCodeWithLanguage(string $code, string $languageCode): JobCategoryResource
    {
        $jobCategory = $this->jobCategoryRepository->getByCodeWithAttributes($code);

        if (!$jobCategory) {
            throw new \Exception('Job category not found');
        }

        // Load translation for the specified language
        $jobCategory->load(['translations' => function ($query) use ($languageCode) {
            $query->where('language_code', $languageCode);
        }]);

        return new JobCategoryResource($jobCategory);
    }

    /**
     * Create translations for a job category.
     */
    private function createTranslations(JobCategory $jobCategory, array $translations): void
    {
        foreach ($translations as $translationData) {
            JobCategoryTranslation::create([
                'job_category_id' => $jobCategory->id,
                'language_code' => $translationData['language_code'],
                'name' => $translationData['name'],
                'description' => $translationData['description'] ?? null,
            ]);
        }
    }

    /**
     * Update translations for a job category.
     */
    private function updateTranslations(JobCategory $jobCategory, array $translations): void
    {
        foreach ($translations as $translationData) {
            // Check if translation exists
            $translation = JobCategoryTranslation::where('job_category_id', $jobCategory->id)
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
                JobCategoryTranslation::create([
                    'job_category_id' => $jobCategory->id,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }
        }
    }

    /**
     * Create attributes for a job category.
     */
    private function createAttributes(JobCategory $jobCategory, array $attributes): void
    {
        foreach ($attributes as $attributeData) {
            JobAttribute::create([
                'job_category_id' => $jobCategory->id,
                'code' => $attributeData['code'],
                'name' => $attributeData['name'],
                'type' => $attributeData['type'] ?? 'text',
                'options' => $attributeData['options'] ?? null,
                'is_required' => $attributeData['is_required'] ?? false,
                'sort_order' => $attributeData['sort_order'] ?? 0,
            ]);
        }
    }

    /**
     * Update attributes for a job category.
     */
    private function updateAttributes(JobCategory $jobCategory, array $attributes): void
    {
        foreach ($attributes as $attributeData) {
            // Check if attribute exists
            $attribute = JobAttribute::where('job_category_id', $jobCategory->id)
                ->where('code', $attributeData['code'])
                ->first();

            if ($attribute) {
                // Update existing attribute
                $attribute->update([
                    'name' => $attributeData['name'] ?? $attribute->name,
                    'type' => $attributeData['type'] ?? $attribute->type,
                    'options' => $attributeData['options'] ?? $attribute->options,
                    'is_required' => $attributeData['is_required'] ?? $attribute->is_required,
                    'sort_order' => $attributeData['sort_order'] ?? $attribute->sort_order,
                ]);
            } else {
                // Create new attribute
                JobAttribute::create([
                    'job_category_id' => $jobCategory->id,
                    'code' => $attributeData['code'],
                    'name' => $attributeData['name'],
                    'type' => $attributeData['type'] ?? 'text',
                    'options' => $attributeData['options'] ?? null,
                    'is_required' => $attributeData['is_required'] ?? false,
                    'sort_order' => $attributeData['sort_order'] ?? 0,
                ]);
            }
        }
    }
}
