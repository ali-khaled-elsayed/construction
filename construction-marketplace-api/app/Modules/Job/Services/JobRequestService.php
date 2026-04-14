<?php

namespace App\Modules\Job\Services;

use App\Models\Job;
use App\Models\JobRequest;
use App\Modules\Job\Enums\DescriptionType;
use App\Modules\Job\Enums\JobStatus;
use App\Modules\Job\Repositories\JobRequestRepository;
use App\Modules\Job\Requests\ListAllJobRequestsRequest;
use App\Modules\Job\Resources\JobRequestCollection;

class JobRequestService
{
    public function __construct(private JobRequestRepository $jobRequestRepository) {}

    public function createJobRequest(array $data): JobRequest
    {
        $jobRequestData = $this->constructJobRequestModel($data);
        // $jobRequestData['customer_id'] = $data['customer_id'];
        $jobRequestData['customer_id'] = 1;

        $jobRequest = $this->jobRequestRepository->create($jobRequestData);

        // Handle basic description
        if ($data['description_type'] === DescriptionType::BASIC->value && isset($data['basic_description'])) {
            $basicDescription = $jobRequest->basicDescription()->create($data['basic_description']);

            // Create a single job for the entire request
            if (isset($data['jobs']) && is_array($data['jobs'])) {
                foreach ($data['jobs'] as $jobData) {
                    $jobRequest->jobs()->create([
                        'category_id' => $jobData['category_id'],
                        'room_id' => null, // No specific room for basic description
                        'fee_amount' => $jobData['fee_amount'] ?? 0,
                        'size' => $jobData['size'] ?? 'medium',
                        'description' => $jobData['description'] ?? null,
                        'urgency' => $jobData['urgency'] ?? 'standard',
                        'status' => JobStatus::OPEN->value,
                    ]);
                }
            }
        }

        // Handle detailed description with rooms
        if ($data['description_type'] === DescriptionType::DETAILED->value && isset($data['rooms'])) {
            foreach ($data['rooms'] as $roomData) {
                $room = $jobRequest->rooms()->create([
                    'room_type_id' => $roomData['room_type_id'],
                    'area' => $roomData['area'] ?? null,
                ]);

                // Create jobs for each room
                if (isset($roomData['jobs']) && is_array($roomData['jobs'])) {
                    foreach ($roomData['jobs'] as $jobData) {
                        $jobRequest->jobs()->create([
                            'category_id' => $jobData['category_id'],
                            'room_id' => $room->id, // Link job to specific room
                            'fee_amount' => $jobData['fee_amount'] ?? 0,
                            'size' => $jobData['size'] ?? 'medium',
                            'description' => $jobData['description'] ?? null,
                            'urgency' => $jobData['urgency'] ?? 'standard',
                            'status' => JobStatus::OPEN->value,
                        ]);
                    }
                }
            }
        }

        return $jobRequest->fresh(['basicDescription', 'rooms.roomType', 'jobs.category']);
    }

    public function updateJobRequest($id, $request)
    {
        $jobRequest = $this->jobRequestRepository->find($id);

        // Update job request basic info
        $jobRequestData = $this->constructJobRequestModel($request);
        $jobRequest->update($jobRequestData);

        // Update or create jobs for basic description
        if (isset($request['jobs']) && is_array($request['jobs'])) {
            // Delete existing jobs without room_id (basic description jobs)
            $jobRequest->jobs()->whereNull('room_id')->delete();

            // Create new jobs
            foreach ($request['jobs'] as $jobData) {
                $jobRequest->jobs()->create([
                    'category_id' => $jobData['category_id'],
                    'room_id' => null,
                    'fee_amount' => $jobData['fee_amount'] ?? 0,
                    'size' => $jobData['size'] ?? 'medium',
                    'description' => $jobData['description'] ?? null,
                    'urgency' => $jobData['urgency'] ?? 'standard',
                    'status' => JobStatus::OPEN->value,
                ]);
            }
        }

        // Update rooms and their jobs for detailed description
        if (isset($request['rooms']) && is_array($request['rooms'])) {
            foreach ($request['rooms'] as $roomData) {
                if (isset($roomData['id'])) {
                    // Update existing room
                    $room = $jobRequest->rooms()->find($roomData['id']);
                    if ($room) {
                        $room->update([
                            'room_type_id' => $roomData['room_type_id'] ?? $room->room_type_id,
                            'area' => $roomData['area'] ?? $room->area,
                        ]);

                        // Update room's jobs
                        if (isset($roomData['jobs']) && is_array($roomData['jobs'])) {
                            $room->jobs()->delete();
                            foreach ($roomData['jobs'] as $jobData) {
                                $jobRequest->jobs()->create([
                                    'category_id' => $jobData['category_id'],
                                    'room_id' => $room->id,
                                    'fee_amount' => $jobData['fee_amount'] ?? 0,
                                    'size' => $jobData['size'] ?? 'medium',
                                    'description' => $jobData['description'] ?? null,
                                    'urgency' => $jobData['urgency'] ?? 'standard',
                                    'status' => JobStatus::OPEN->value,
                                ]);
                            }
                        }
                    }
                } else {
                    // Create new room
                    $room = $jobRequest->rooms()->create([
                        'room_type_id' => $roomData['room_type_id'],
                        'area' => $roomData['area'] ?? null,
                    ]);

                    // Create room's jobs
                    if (isset($roomData['jobs']) && is_array($roomData['jobs'])) {
                        foreach ($roomData['jobs'] as $jobData) {
                            $jobRequest->jobs()->create([
                                'category_id' => $jobData['category_id'],
                                'room_id' => $room->id,
                                'fee_amount' => $jobData['fee_amount'] ?? 0,
                                'size' => $jobData['size'] ?? 'medium',
                                'description' => $jobData['description'] ?? null,
                                'urgency' => $jobData['urgency'] ?? 'standard',
                                'status' => JobStatus::OPEN->value,
                            ]);
                        }
                    }
                }
            }
        }

        return $jobRequest->fresh(['basicDescription', 'rooms.roomType', 'jobs.category']);
    }

    public function deleteJobRequest($id)
    {
        return $this->jobRequestRepository->delete($id);
    }

    public function listAllJobRequests(array $queryParameters)
    {
        $listAllJobRequests = (new ListAllJobRequestsRequest)->constructQueryCriteria($queryParameters);
        $jobRequests = $this->jobRequestRepository->findAllBy($listAllJobRequests);
        return [
            'data' => new JobRequestCollection($jobRequests['data']),
            'count' => $jobRequests['count']
        ];
    }

    public function getJobRequestById($id)
    {
        return $this->jobRequestRepository->getWithRelationships($id);
    }

    public function constructJobRequestModel($request)
    {
        $jobRequestModel = [
            'unit_type' => $request['unit_type'] ?? null,
            'job_type' => $request['job_type'] ?? null,
            'service_type' => $request['service_type'] ?? null,
            'description_type' => $request['description_type'] ?? null,
            'city_id' => $request['city_id'] ?? null,
            'country_code' => $request['country_code'] ?? null,
            'address' => $request['address'] ?? null,
        ];
        return $jobRequestModel;
    }
}
