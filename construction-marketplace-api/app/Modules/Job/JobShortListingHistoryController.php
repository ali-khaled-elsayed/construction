<?php

namespace App\Modules\Job;

use App\Http\Controllers\Controller;
use App\Modules\Job\Requests\ListJobShortListingHistoryRequest;
use App\Modules\Job\Resources\JobShortListingHistoryResource;
use App\Modules\Job\Resources\JobShortListingHistoryCollection;
use App\Modules\Job\Services\JobShortListingHistoryService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;

class JobShortListingHistoryController extends Controller
{
    public function __construct(private JobShortListingHistoryService $jobShortListingHistoryService) {}

    public function getHistory($id)
    {
        $history = $this->jobShortListingHistoryService->getHistoryWithRelationships($id);
        if ($history) {
            return successJsonResponse(new JobShortListingHistoryResource($history));
        }
        return errorJsonResponse("History record not found", HttpStatusCodeEnum::Not_Found->value);
    }

    public function listHistoryByShortListing($shortListingId)
    {
        $history = $this->jobShortListingHistoryService->getHistoryByShortListing($shortListingId);
        return successJsonResponse(JobShortListingHistoryCollection::make($history));
    }

    public function listHistoryByUser($userId)
    {
        $history = $this->jobShortListingHistoryService->getHistoryByUser($userId);
        return successJsonResponse(JobShortListingHistoryCollection::make($history));
    }

    public function listAllHistory(ListJobShortListingHistoryRequest $request)
    {
        $filters = $request->validated();
        $history = $this->jobShortListingHistoryService->getHistoryWithFilters($filters);
        return successJsonResponse(JobShortListingHistoryCollection::make($history));
    }
}
