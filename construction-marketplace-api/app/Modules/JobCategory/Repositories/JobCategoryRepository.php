<?php

namespace App\Modules\JobCategory\Repositories;

use App\Models\JobCategory;
use App\Modules\JobCategory\Repositories\Contracts\JobCategoryRepositoryInterface;
use App\Modules\Shared\Repositories\Contracts\BaseRepositoryInterface;

class JobCategoryRepository implements JobCategoryRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \App\Models\JobCategory $model
     */
    public function __construct(private JobCategory $model) {}

    /**
     * Get all active job categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Get job categories with translations for a specific language.
     *
     * @param string $languageCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithTranslations(string $languageCode)
    {
        return $this->model
            ->active()
            ->ordered()
            ->with(['translations' => function ($query) use ($languageCode) {
                $query->where('language_code', $languageCode);
            }])
            ->get();
    }

    /**
     * Get job category with attributes.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByIdWithAttributes(int $id)
    {
        return $this->model
            ->with(['translations', 'attributes' => function ($query) {
                $query->ordered();
            }])
            ->find($id);
    }

    /**
     * Get job category by code.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Get job category by code with attributes.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByCodeWithAttributes(string $code)
    {
        return $this->model
            ->with(['translations', 'attributes' => function ($query) {
                $query->ordered();
            }])
            ->where('code', $code)
            ->first();
    }

    /**
     * Get the base model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Find a model by its primary key.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Paginate the models.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Create a new model.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update an existing model.
     *
     * @param int $id
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $attributes)
    {
        $model = $this->findOrFail($id);
        $model->update($attributes);
        return $model->fresh();
    }

    /**
     * Delete a model.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }
}
