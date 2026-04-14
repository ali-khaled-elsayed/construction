<?php

namespace App\Modules\JobHistory\Services;

use App\Models\JobHistory;
use App\Modules\JobHistory\Repositories\JobHistoryRepository;
use App\Modules\JobHistory\Resources\JobHistoryResource;
use App\Modules\JobHistory\Resources\JobHistoryCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobHistoryService
{
    public function __construct(private JobHistoryRepository $jobHistoryRepository) {}

    /**
     * Get all job histories for a specific job.
     */
    public function getByJobId(int $jobId): JobHistoryCollection
    {
        $histories = $this->jobHistoryRepository->getByJobId($jobId);
        return new JobHistoryCollection($histories);
    }

    /**
     * Get job histories with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, array $queryParameters = []): array
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $histories = $this->jobHistoryRepository->getByJobIdWithPagination($jobId, $perPage);

        return [
            'data' => new JobHistoryCollection($histories->items()),
            'meta' => [
                'current_page' => $histories->currentPage(),
                'last_page' => $histories->lastPage(),
                'per_page' => $histories->perPage(),
                'total' => $histories->total(),
            ],
        ];
    }

    /**
     * Get job histories by action type.
     */
    public function getByAction(string $action): JobHistoryCollection
    {
        $histories = $this->jobHistoryRepository->getByAction($action);
        return new JobHistoryCollection($histories);
    }

    /**
     * Get job histories by user.
     */
    public function getByUserId(int $userId): JobHistoryCollection
    {
        $histories = $this->jobHistoryRepository->getByUserId($userId);
        return new JobHistoryCollection($histories);
    }

    /**
     * Create a new job history record.
     */
    public function create(array $data): JobHistoryResource
    {
        $history = $this->jobHistoryRepository->create($data);
        return new JobHistoryResource($history);
    }

    /**
     * Get job status changes for a specific job.
     */
    public function getStatusChanges(int $jobId): JobHistoryCollection
    {
        $histories = $this->jobHistoryRepository->getStatusChanges($jobId);
        return new JobHistoryCollection($histories);
    }

    /**
     * Get the latest history record for a job.
     */
    public function getLatestForJob(int $jobId): ?JobHistoryResource
    {
        $history = $this->jobHistoryRepository->getLatestForJob($jobId);
        return $history ? new JobHistoryResource($history) : null;
    }

    /**
     * Get job history by ID.
     */
    public function getById(int $id): JobHistoryResource
    {
        $history = $this->jobHistoryRepository->find($id);

        if (!$history) {
            throw new \Exception('Job history record not found');
        }

        return new JobHistoryResource($history);
    }

    /**
     * Create a status change history record.
     */
    public function createStatusChange(int $jobId, string $oldStatus, string $newStatus, ?int $userId = null, ?string $description = null): JobHistoryResource
    {
        $data = [
            'job_id' => $jobId,
            'user_id' => $userId,
            'action' => 'status_changed',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'description' => $description,
        ];

        return $this->create($data);
    }

    /**
     * Create a general action history record.
     */
    public function createAction(int $jobId, string $action, ?int $userId = null, ?string $description = null, ?array $changes = null): JobHistoryResource
    {
        $data = [
            'job_id' => $jobId,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'changes' => $changes,
        ];

        return $this->create($data);
    }

    /**
     * Get available action types.
     */
    public function getActionTypes(): array
    {
        return JobHistory::getActionTypes();
    }

    /**
     * Get job timeline summary.
     */
    public function getTimelineSummary(int $jobId): array
    {
        $allHistories = $this->getByJobId($jobId);
        $statusChanges = $this->getStatusChanges($jobId);

        return [
            'total_actions' => $allHistories->count(),
            'status_changes' => $statusChanges->count(),
            'first_action' => $allHistories->last(), // Oldest
            'last_action' => $allHistories->first(), // Newest
            'status_history' => $statusChanges,
            'action_summary' => $this->getActionSummary($jobId),
        ];
    }

    /**
     * Get action summary for a job.
     */
    private function getActionSummary(int $jobId): array
    {
        $histories = $this->jobHistoryRepository->getByJobId($jobId);
        $summary = [];

        foreach ($histories as $history) {
            $action = $history->action;
            if (!isset($summary[$action])) {
                $summary[$action] = 0;
            }
            $summary[$action]++;
        }

        return $summary;
    }
}
