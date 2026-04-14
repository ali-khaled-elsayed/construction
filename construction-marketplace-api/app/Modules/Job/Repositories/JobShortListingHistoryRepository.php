<?php

namespace App\Modules\Job\Repositories;

use App\Models\JobShortListingHistory;
use App\Modules\Shared\Repositories\BaseRepository;

class JobShortListingHistoryRepository extends BaseRepository
{
    public function __construct(private JobShortListingHistory $model)
    {
        parent::__construct($model);
    }

    /**
     * Get history by short listing ID
     */
    public function getByShortListing(int $shortListingId)
    {
        return $this->model
            ->where('short_listing_id', $shortListingId)
            ->with(['shortListing', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get history by user ID
     */
    public function getByUser(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['shortListing.job', 'shortListing.provider', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get history with filters
     */
    public function getWithFilters(array $filters)
    {
        $query = $this->model->query()
            ->with(['shortListing.job', 'shortListing.provider', 'user']);

        // Apply filters
        if (isset($filters['short_listing_id'])) {
            $query->where('short_listing_id', $filters['short_listing_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['old_status'])) {
            $query->where('old_status', $filters['old_status']);
        }

        if (isset($filters['new_status'])) {
            $query->where('new_status', $filters['new_status']);
        }

        if (isset($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'LIKE', $searchTerm)
                    ->orWhere('old_status', 'LIKE', $searchTerm)
                    ->orWhere('new_status', 'LIKE', $searchTerm);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get history record with relationships
     */
    public function getWithRelationships(int $id)
    {
        return $this->model
            ->with([
                'shortListing.job',
                'shortListing.provider',
                'user'
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
                'shortListing.job',
                'shortListing.provider',
                'user'
            ])->skip($offset)->take($limit)->orderBy($sortBy, $sort)->get(),
        ];
    }
}
