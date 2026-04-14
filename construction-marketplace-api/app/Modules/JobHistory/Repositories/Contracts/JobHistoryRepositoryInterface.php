<?php

namespace App\Modules\JobHistory\Repositories\Contracts;

use App\Models\JobHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface JobHistoryRepositoryInterface
{
    /**
     * Get all job histories for a specific job.
     */
    public function getByJobId(int $jobId): Collection;

    /**
     * Get job histories by action type.
     */
    public function getByAction(string $action): Collection;

    /**
     * Get job histories by user.
     */
    public function getByUserId(int $userId): Collection;

    /**
     * Get job histories with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new job history record.
     */
    public function create(array $data): JobHistory;

    /**
     * Get job status changes for a specific job.
     */
    public function getStatusChanges(int $jobId): Collection;

    /**
     * Get the latest history record for a job.
     */
    public function getLatestForJob(int $jobId): ?JobHistory;

    /**
     * Get job history by ID.
     */
    public function find(int $id): ?JobHistory;
}
