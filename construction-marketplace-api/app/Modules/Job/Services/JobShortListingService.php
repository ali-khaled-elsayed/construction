<?php

namespace App\Modules\Job\Services;

use App\Models\JobShortListing;
use App\Modules\Job\Repositories\JobShortListingRepository;
use App\Modules\Job\Repositories\JobShortListingHistoryRepository;

class JobShortListingService
{
    public function __construct(
        private JobShortListingRepository $jobShortListingRepository,
        private JobShortListingHistoryRepository $jobShortListingHistoryRepository
    ) {}

    public function createShortListing(array $data): JobShortListing
    {
        $shortListing = $this->jobShortListingRepository->create($data);

        // Create history record
        $this->jobShortListingHistoryRepository->create([
            'short_listing_id' => $shortListing->id,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'old_status' => null,
            'new_status' => $shortListing->status,
            'description' => 'Short listing created',
            'changes' => $data,
        ]);

        return $shortListing;
    }

    public function updateShortListing(int $id, array $data): ?JobShortListing
    {
        $shortListing = $this->jobShortListingRepository->find($id);
        if (!$shortListing) {
            return null;
        }

        $oldStatus = $shortListing->status;
        $shortListing = $this->jobShortListingRepository->update($id, $data);

        // Create history record if status changed
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $this->jobShortListingHistoryRepository->create([
                'short_listing_id' => $shortListing->id,
                'user_id' => $data['user_id'] ?? auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $data['status'],
                'description' => $data['description'] ?? 'Status updated',
                'changes' => $data,
            ]);
        }

        return $shortListing;
    }

    public function deleteShortListing(int $id): ?JobShortListing
    {
        $shortListing = $this->jobShortListingRepository->find($id);
        if (!$shortListing) {
            return null;
        }

        // Delete history records first
        $this->jobShortListingHistoryRepository->findAllBy(['filters' => ['short_listing_id' => $id]])['data']->each->delete();

        return $this->jobShortListingRepository->delete($id);
    }

    public function getShortListingsByJob(int $jobId)
    {
        return $this->jobShortListingRepository->getByJob($jobId);
    }

    public function getShortListingsByProvider(int $providerId)
    {
        return $this->jobShortListingRepository->getByProvider($providerId);
    }

    public function getShortListingWithRelationships(int $id)
    {
        return $this->jobShortListingRepository->getWithRelationships($id);
    }

    public function getShortListingsWithFilters(array $filters)
    {
        return $this->jobShortListingRepository->getWithFilters($filters);
    }

    public function getHistoryByShortListing(int $shortListingId)
    {
        return $this->jobShortListingHistoryRepository->getByShortListing($shortListingId);
    }
}
