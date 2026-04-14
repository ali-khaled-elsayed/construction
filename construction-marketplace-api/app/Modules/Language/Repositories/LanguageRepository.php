<?php

namespace App\Modules\Language\Repositories;

use App\Models\Language;
use App\Modules\Shared\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LanguageRepository extends BaseRepository
{
    public function __construct(private Language $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a language by its code.
     */
    public function findByCode(string $code): ?Model
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Find a language by its code with relationships.
     */
    public function findByCodeWith(string $code, array $relationships = []): ?Model
    {
        return $this->model->with($relationships)->where('code', $code)->first();
    }

    /**
     * Find a language by its ID with relationships.
     */
    public function findWith(int $id, array $relationships = []): ?Model
    {
        return $this->model->with($relationships)->find($id);
    }

    /**
     * Get all languages.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Get all languages with relationships.
     */
    public function allWith(array $relationships = [], array $columns = ['*']): Collection
    {
        return $this->model->with($relationships)->get($columns);
    }

    /**
     * Get paginated languages.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Get the default language.
     */
    public function getDefault(): ?Model
    {
        return $this->model->where('is_default', true)->first();
    }

    /**
     * Search languages by name.
     */
    public function search(string $searchTerm): Collection
    {
        return $this->model->where('name', 'like', "%{$searchTerm}%")->get();
    }
}
