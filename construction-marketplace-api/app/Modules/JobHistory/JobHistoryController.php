<?php

namespace App\Modules\JobHistory;

use App\Http\Controllers\Controller;
use App\Modules\JobHistory\Services\JobHistoryService;
use Illuminate\Http\Request;

class JobHistoryController extends Controller
{
    public function __construct(private JobHistoryService $jobHistoryService) {}

    /**
     * Get all job histories for a specific job.
     */
    public function getByJobId(int $jobId)
    {
        $histories = $this->jobHistoryService->getByJobId($jobId);
        return successJsonResponse(
            $histories,
            __('job_history.success.get_job_history')
        );
    }

    /**
     * Get job histories with pagination.
     */
    public function getByJobIdPaginated(int $jobId, Request $request)
    {
        $result = $this->jobHistoryService->getByJobIdWithPagination($jobId, $request->all());
        return successJsonResponse(
            data_get($result, 'data'),
            __('job_history.success.get_job_history'),
            data_get($result, 'meta')
        );
    }

    /**
     * Get job histories by action type.
     */
    public function getByAction(string $action)
    {
        $histories = $this->jobHistoryService->getByAction($action);
        return successJsonResponse(
            $histories,
            __('job_history.success.get_job_history_by_action')
        );
    }

    /**
     * Get job histories by user.
     */
    public function getByUserId(int $userId)
    {
        $histories = $this->jobHistoryService->getByUserId($userId);
        return successJsonResponse(
            $histories,
            __('job_history.success.get_job_history_by_user')
        );
    }

    /**
     * Get job status changes for a specific job.
     */
    public function getStatusChanges(int $jobId)
    {
        $histories = $this->jobHistoryService->getStatusChanges($jobId);
        return successJsonResponse(
            $histories,
            __('job_history.success.get_status_changes')
        );
    }

    /**
     * Get the latest history record for a job.
     */
    public function getLatestForJob(int $jobId)
    {
        $history = $this->jobHistoryService->getLatestForJob($jobId);

        if (!$history) {
            return errorJsonResponse(__('job_history.errors.no_history_found'));
        }

        return successJsonResponse(
            $history,
            __('job_history.success.get_latest_history')
        );
    }

    /**
     * Get job history by ID.
     */
    public function getById(int $id)
    {
        $history = $this->jobHistoryService->getById($id);
        return successJsonResponse(
            $history,
            __('job_history.success.get_job_history')
        );
    }

    /**
     * Get job timeline summary.
     */
    public function getTimelineSummary(int $jobId)
    {
        $summary = $this->jobHistoryService->getTimelineSummary($jobId);
        return successJsonResponse(
            $summary,
            __('job_history.success.get_timeline_summary')
        );
    }

    /**
     * Get available action types.
     */
    public function getActionTypes()
    {
        $actionTypes = $this->jobHistoryService->getActionTypes();
        return successJsonResponse(
            $actionTypes,
            __('job_history.success.get_action_types')
        );
    }
}
