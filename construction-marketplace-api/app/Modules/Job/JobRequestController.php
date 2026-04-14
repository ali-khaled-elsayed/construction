<?php

namespace App\Modules\Job;

use App\Http\Controllers\Controller;
use App\Modules\Job\Requests\CreateJobRequestRequest;
use App\Modules\Job\Requests\ListJobRequestsRequest;
use App\Modules\Job\Requests\UpdateJobRequestRequest;
use App\Modules\Job\Resources\JobRequestResource;
use App\Modules\Job\Services\JobRequestService;
use App\Modules\Shared\Enums\HttpStatusCodeEnum;

class JobRequestController extends Controller
{

    public function __construct(private JobRequestService $jobRequestService) {}

    public function createJobRequest(CreateJobRequestRequest $request)
    {
        $data = $request->validated();
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('jobRequest', 'public');
        //     $data['image'] = $path;
        // }
        $jobRequest = $this->jobRequestService->createJobRequest($data);
        return successJsonResponse(new JobRequestResource($jobRequest), __('jobRequest.success.create_JobRequest'));
    }

    public function updateJobRequest($id, UpdateJobRequestRequest $request)
    {
        $data = $request->validated();
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('JobRequests', 'public');
        //     $data['image'] = $path;
        // }
        $jobRequest = $this->jobRequestService->updateJobRequest($id, $data);
        return successJsonResponse(new JobRequestResource($jobRequest), __('jobRequest.success.update_jobRequest'));
    }

    public function deleteJobRequest($id)
    {
        $jobRequest = $this->jobRequestService->deleteJobRequest($id);
        if ($jobRequest == true) {
            return successJsonResponse([], __('jobRequest.success.delete_jobRequest jobRequest_id = ' . $jobRequest['id']));
        } else {
            return errorJsonResponse("There is No JobRequest with id = " . $id, HttpStatusCodeEnum::Not_Found->value);
        }
    }

    public function listAllJobRequests(ListJobRequestsRequest $request)
    {
        $jobRequests = $this->jobRequestService->listAllJobRequests($request->validated());
        return successJsonResponse(data_get($jobRequests, 'data'), __('jobRequests.success.get_all_jobRequests'), data_get($jobRequests, 'count'));
    }

    public function getJobRequestById($jobRequestId)
    {
        $jobRequest = $this->jobRequestService->getJobRequestById($jobRequestId);
        if (!$jobRequest) {
            return errorJsonResponse("jobRequest $jobRequestId is not found!", HttpStatusCodeEnum::Not_Found->value);
        }
        return successJsonResponse(new JobRequestResource($jobRequest), __('jobRequest.success.jobRequest_details'));
    }
}
