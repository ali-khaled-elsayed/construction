<?php

namespace App\Modules\Location\Repositories;

use App\Models\City;
use App\Modules\Shared\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CityRepository extends BaseRepository
{
    public function __construct(private City $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a city by its ID with relationships.
     */
    public function findWith(int $id, array $relationships = []): ?Model
    {
        return $this->model->with($relationships)->find($id);
    }

    /**
     * Get all cities.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Get all cities with relationships.
     */
    public function allWith(array $relationships = [], array $columns = ['*']): Collection
    {
        return $this->model->with($relationships)->get($columns);
    }

    /**
     * Get paginated cities.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Get cities by country code.
     */
    public function getByCountry(string $countryCode): Collection
    {
        return $this->model->where('country_code', $countryCode)->get();
    }

    /**
     * Search cities by name.
     */
    public function search(string $searchTerm): Collection
    {
        return $this->model->where('name', 'like', "%{$searchTerm}%")->get();
    }
}
