<?php

namespace App\Modules\Job\Services;

use App\Models\JobShortListingHistory;
use App\Modules\Job\Repositories\JobShortListingHistoryRepository;

class JobShortListingHistoryService
{
    public function __construct(private JobShortListingHistoryRepository $jobShortListingHistoryRepository) {}

    public function createHistory(array $data): JobShortListingHistory
    {
        return $this->jobShortListingHistoryRepository->create($data);
    }

    public function getHistoryByShortListing(int $shortListingId)
    {
        return $this->jobShortListingHistoryRepository->getByShortListing($shortListingId);
    }

    public function getHistoryByUser(int $userId)
    {
        return $this->jobShortListingHistoryRepository->getByUser($userId);
    }

    public function getHistoryWithFilters(array $filters)
    {
        return $this->jobShortListingHistoryRepository->getWithFilters($filters);
    }

    public function getHistoryWithRelationships(int $id)
    {
        return $this->jobShortListingHistoryRepository->getWithRelationships($id);
    }
}
