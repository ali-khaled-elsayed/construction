<?php

namespace App\Modules\Job;

use App\Http\Controllers\Controller;
use App\Modules\Job\Requests\CreateJobShortListingRequest;
use App\Modules\Job\Requests\UpdateJobShortListingRequest;
use App\Modules\Job\Requests\ListJobShortListingsRequest;
use App\Modules\Job\Resources\JobShortListingResource;
use App\Modules\Job\Resources\JobShortListingCollection;
use App\Modules\Job\Services\JobShortListingService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;

class JobShortListingController extends Controller
{
    public function __construct(private JobShortListingService $jobShortListingService) {}

    public function createShortListing(CreateJobShortListingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $shortListing = $this->jobShortListingService->createShortListing($data);
        return successJsonResponse(new JobShortListingResource($shortListing), __('shortListing.success.create_shortListing'));
    }

    public function updateShortListing($id, UpdateJobShortListingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $shortListing = $this->jobShortListingService->updateShortListing($id, $data);
        if ($shortListing) {
            return successJsonResponse(new JobShortListingResource($shortListing), __('shortListing.success.update_shortListing'));
        }
        return errorJsonResponse("Short listing not found", HttpStatusCodeEnum::Not_Found->value);
    }

    public function deleteShortListing($id)
    {
        $shortListing = $this->jobShortListingService->deleteShortListing($id);
        if ($shortListing) {
            return successJsonResponse([], __('shortListing.success.delete_shortListing'));
        }
        return errorJsonResponse("Short listing not found", HttpStatusCodeEnum::Not_Found->value);
    }

    public function getShortListing($id)
    {
        $shortListing = $this->jobShortListingService->getShortListingWithRelationships($id);
        if ($shortListing) {
            return successJsonResponse(new JobShortListingResource($shortListing));
        }
        return errorJsonResponse("Short listing not found", HttpStatusCodeEnum::Not_Found->value);
    }

    public function listShortListingsByJob($jobId)
    {
        $shortListings = $this->jobShortListingService->getShortListingsByJob($jobId);
        return successJsonResponse(JobShortListingCollection::make($shortListings));
    }

    public function listShortListingsByProvider($providerId)
    {
        $shortListings = $this->jobShortListingService->getShortListingsByProvider($providerId);
        return successJsonResponse(JobShortListingCollection::make($shortListings));
    }

    public function listAllShortListings(ListJobShortListingsRequest $request)
    {
        $filters = $request->validated();
        $shortListings = $this->jobShortListingService->getShortListingsWithFilters($filters);
        return successJsonResponse(JobShortListingCollection::make($shortListings));
    }
}
