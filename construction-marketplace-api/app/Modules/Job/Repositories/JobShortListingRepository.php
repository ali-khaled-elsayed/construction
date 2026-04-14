<?php

namespace App\Modules\Job\Repositories;

use App\Models\JobShortListing;
use App\Modules\Shared\Repositories\BaseRepository;

class JobShortListingRepository extends BaseRepository
{
    public function __construct(private JobShortListing $model)
    {
        parent::__construct($model);
    }

    /**
     * Get short listings by job ID
     */
    public function getByJob(int $jobId)
    {
        return $this->model
            ->where('job_id', $jobId)
            ->with(['job', 'provider'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get short listings by provider ID
     */
    public function getByProvider(int $providerId)
    {
        return $this->model
            ->where('provider_id', $providerId)
            ->with(['job', 'provider'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get short listings with filters
     */
    public function getWithFilters(array $filters)
    {
        $query = $this->model->query()
            ->with(['job', 'provider']);

        // Apply filters
        if (isset($filters['job_id'])) {
            $query->where('job_id', $filters['job_id']);
        }

        if (isset($filters['provider_id'])) {
            $query->where('provider_id', $filters['provider_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'LIKE', $searchTerm)
                    ->orWhereHas('job', function ($q) use ($searchTerm) {
                        $q->where('title', 'LIKE', $searchTerm);
                    })
                    ->orWhereHas('provider.user', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', $searchTerm);
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get short listing with relationships
     */
    public function getWithRelationships(int $id)
    {
        return $this->model
            ->with([
                'job',
                'provider',
                'history.user'
            ])
            ->find($id);
    }

    /**
     * Find all by criteria
     */
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
                'job',
                'provider',
                'history.user'
            ])->skip($offset)->take($limit)->orderBy($sortBy, $sort)->get(),
        ];
    }
}
