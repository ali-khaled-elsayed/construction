<?php

namespace App\Modules\JobHistory\Repositories;

use App\Models\JobHistory;
use App\Modules\JobHistory\Repositories\Contracts\JobHistoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobHistoryRepository implements JobHistoryRepositoryInterface
{
    /**
     * Get all job histories for a specific job.
     */
    public function getByJobId(int $jobId): Collection
    {
        return JobHistory::with(['user'])
            ->forJob($jobId)
            ->ordered()
            ->get();
    }

    /**
     * Get job histories by action type.
     */
    public function getByAction(string $action): Collection
    {
        return JobHistory::with(['user', 'job'])
            ->byAction($action)
            ->ordered()
            ->get();
    }

    /**
     * Get job histories by user.
     */
    public function getByUserId(int $userId): Collection
    {
        return JobHistory::with(['job'])
            ->byUser($userId)
            ->ordered()
            ->get();
    }

    /**
     * Get job histories with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, int $perPage = 15): LengthAwarePaginator
    {
        return JobHistory::with(['user'])
            ->forJob($jobId)
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Create a new job history record.
     */
    public function create(array $data): JobHistory
    {
        return JobHistory::create($data);
    }

    /**
     * Get job status changes for a specific job.
     */
    public function getStatusChanges(int $jobId): Collection
    {
        return JobHistory::with(['user'])
            ->forJob($jobId)
            ->byAction('status_changed')
            ->ordered()
            ->get();
    }

    /**
     * Get the latest history record for a job.
     */
    public function getLatestForJob(int $jobId): ?JobHistory
    {
        return JobHistory::with(['user'])
            ->forJob($jobId)
            ->ordered()
            ->first();
    }

    /**
     * Get job history by ID.
     */
    public function find(int $id): ?JobHistory
    {
        return JobHistory::with(['user', 'job'])->find($id);
    }
}
