<?php

namespace App\Modules\Job\Repositories;

use App\Models\JobRequest;
use App\Modules\Shared\Repositories\BaseRepository;

class JobRequestRepository extends BaseRepository
{
    public function __construct(private JobRequest $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getByCustomer(int $customerId)
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with(['customer', 'city', 'country', 'basicDescription', 'rooms.roomType', 'jobs.category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getWithFilters(array $filters)
    {
        $query = $this->model->query()
            ->with(['customer', 'city', 'country', 'basicDescription', 'rooms.roomType', 'jobs.category']);

        // Apply filters
        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (isset($filters['country_code'])) {
            $query->where('country_code', $filters['country_code']);
        }

        if (isset($filters['job_type'])) {
            $query->where('job_type', $filters['job_type']);
        }

        if (isset($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        if (isset($filters['description_type'])) {
            $query->where('description_type', $filters['description_type']);
        }

        if (isset($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $query->where(function ($q) use ($searchTerm) {
                $q->where('unit_type', 'LIKE', $searchTerm)
                    ->orWhere('address', 'LIKE', $searchTerm)
                    ->orWhereHas('basicDescription', function ($q) use ($searchTerm) {
                        $q->where('description', 'LIKE', $searchTerm);
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    /**
     * @inheritDoc
     */
    public function getWithRelationships(int $id)
    {
        return $this->model
            ->with([
                'customer',
                'city',
                'country',
                'basicDescription',
                'rooms.roomType',
                'jobs' => function ($query) {
                    $query->with(['category', 'room', 'attributeValues.attribute']);
                },
                'comments.user',
                'ratings.rater',
                'ratings.rated'
            ])
            ->find($id);
    }

    public function findAllBy($queryCriteria = [])
    {
        $query = $this->model;
        $limit = data_get($queryCriteria, 'limit', 10);
        $offset = data_get($queryCriteria, 'offset', 0);
        $sortBy = data_get($queryCriteria, 'sortBy', 'id');
        $sort = data_get($queryCriteria, 'sort', 'DESC');
        $filters = data_get($queryCriteria, 'filters', []);
        if (!empty($filters)) {
            $query = $query->where($filters);
        }
        return [
            'count' => $query->count(),
            'data' => $query->with([
                'customer',
                'city',
                'country',
                'basicDescription',
                'rooms.roomType',
                'jobs' => function ($query) {
                    $query->with(['category', 'room', 'attributeValues.attribute']);
                },
                'comments.user',
                'ratings.rater',
                'ratings.rated'
            ])->skip($offset)->take($limit)->orderBy($sortBy, $sort)->get(),
        ];
    }
}
