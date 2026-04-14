<?php

namespace App\Modules\Location\Repositories;

use App\Models\Country;
use App\Modules\Shared\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CountryRepository extends BaseRepository
{
    public function __construct(private Country $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a country by its code.
     */
    public function findByCode(string $code): ?Model
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Find a country by its code with relationships.
     */
    public function findByCodeWith(string $code, array $relationships = []): ?Model
    {
        return $this->model->with($relationships)->where('code', $code)->first();
    }

    /**
     * Find a country by its ID with relationships.
     */
    public function findWith(int $id, array $relationships = []): ?Model
    {
        return $this->model->with($relationships)->find($id);
    }

    /**
     * Get all countries.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Get all countries with relationships.
     */
    public function allWith(array $relationships = [], array $columns = ['*']): Collection
    {
        return $this->model->with($relationships)->get($columns);
    }

    /**
     * Get paginated countries.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Search countries by name.
     */
    public function search(string $searchTerm): Collection
    {
        return $this->model->where('name', 'like', "%{$searchTerm}%")->get();
    }
}
