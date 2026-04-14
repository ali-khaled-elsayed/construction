<?php

namespace App\Modules\JobShortListing\Services;

use App\Models\JobShortListing;
use App\Modules\JobShortListing\Repositories\JobShortListingRepository;

class JobShortListingService
{
    public function __construct(private JobShortListingRepository $jobShortListingRepository) {}

    /**
     * Get all short listings for a specific job.
     */
    public function getByJobId(int $jobId)
    {
        $shortListings = $this->jobShortListingRepository->getByJobId($jobId);
        return new JobShortListingCollection($shortListings);
    }

    /**
     * Get short listings by provider.
     */
    public function getByProviderId(int $providerId)
    {
        $shortListings = $this->jobShortListingRepository->getByProviderId($providerId);
        return new JobShortListingCollection($shortListings);
    }

    /**
     * Get short listings by status.
     */
    public function getByStatus(string $status)
    {
        $shortListings = $this->jobShortListingRepository->getByStatus($status);
        return new JobShortListingCollection($shortListings);
    }

    /**
     * Get short listings with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, array $queryParameters = [])
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $shortListings = $this->jobShortListingRepository->getByJobIdWithPagination($jobId, $perPage);

        return [
            'data' => new JobShortListingCollection($shortListings->items()),
            'meta' => [
                'current_page' => $shortListings->currentPage(),
                'last_page' => $shortListings->lastPage(),
                'per_page' => $shortListings->perPage(),
                'total' => $shortListings->total(),
            ],
        ];
    }

    /**
     * Create a new short listing.
     */
    public function create(array $data)
    {
        $shortListing = $this->jobShortListingRepository->create($data);
        return new JobShortListingResource($shortListing);
    }

    /**
     * Update a short listing.
     */
    public function update(int $id, array $data)
    {
        $shortListing = $this->jobShortListingRepository->update($id, $data);
        return new JobShortListingResource($shortListing);
    }

    /**
     * Delete a short listing.
     */
    public function delete(int $id): bool
    {
        return $this->jobShortListingRepository->delete($id);
    }

    /**
     * Get short listing by ID.
     */
    public function getById(int $id)
    {
        $shortListing = $this->jobShortListingRepository->find($id);

        if (!$shortListing) {
            throw new \Exception('Short listing not found');
        }

        return new JobShortListingResource($shortListing);
    }

    /**
     * Get short listing by job and provider.
     */
    public function getByJobAndProvider(int $jobId, int $providerId)
    {
        $shortListing = $this->jobShortListingRepository->findByJobAndProvider($jobId, $providerId);
        return $shortListing ? new JobShortListingResource($shortListing) : null;
    }

    /**
     * Get short listing history.
     */
    public function getHistory(int $shortListingId)
    {
        $history = $this->jobShortListingRepository->getHistory($shortListingId);
        return new JobShortListingHistoryCollection($history);
    }

    /**
     * Get short listing history with pagination.
     */
    public function getHistoryWithPagination(int $shortListingId, array $queryParameters = [])
    {
        $perPage = $queryParameters['per_page'] ?? 15;
        $history = $this->jobShortListingRepository->getHistoryWithPagination($shortListingId, $perPage);

        return [
            'data' => new JobShortListingHistoryCollection($history->items()),
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ],
        ];
    }

    /**
     * Create short listing history record.
     */
    public function createHistory(array $data)
    {
        $history = $this->jobShortListingRepository->createHistory($data);
        return new JobShortListingHistoryResource($history);
    }

    /**
     * Get short listing status changes.
     */
    public function getStatusChanges(int $shortListingId)
    {
        $history = $this->jobShortListingRepository->getStatusChanges($shortListingId);
        return new JobShortListingHistoryCollection($history);
    }

    /**
     * Get the latest history record for a short listing.
     */
    public function getLatestHistory(int $shortListingId)
    {
        $history = $this->jobShortListingRepository->getLatestHistory($shortListingId);
        return $history ? new JobShortListingHistoryResource($history) : null;
    }

    /**
     * Get short listings that need attention (e.g., pending payments).
     */
    public function getPendingAttention()
    {
        $shortListings = $this->jobShortListingRepository->getPendingAttention();
        return new JobShortListingCollection($shortListings);
    }

    /**
     * Create a new short listing with status change history.
     */
    public function createWithHistory(array $data, ?int $userId = null)
    {
        $shortListing = $this->jobShortListingRepository->create($data);

        // Create initial history record
        $this->createHistory([
            'short_listing_id' => $shortListing->id,
            'user_id' => $userId,
            'old_status' => null,
            'new_status' => $shortListing->status,
            'description' => 'Short listing created',
        ]);

        return new JobShortListingResource($shortListing);
    }

    /**
     * Update short listing status with history.
     */
    public function updateStatus(int $id, string $newStatus, ?int $userId = null, ?string $description = null)
    {
        $shortListing = $this->jobShortListingRepository->find($id);

        if (!$shortListing) {
            throw new \Exception('Short listing not found');
        }

        if (!$shortListing->canChangeStatusTo($newStatus)) {
            throw new \Exception("Invalid status transition from {$shortListing->status} to {$newStatus}");
        }

        $oldStatus = $shortListing->status;

        // Update the status
        $shortListing->update(['status' => $newStatus]);

        // Create history record
        $this->createHistory([
            'short_listing_id' => $shortListing->id,
            'user_id' => $userId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'description' => $description ?? "Status changed from {$oldStatus} to {$newStatus}",
        ]);

        return new JobShortListingResource($shortListing->fresh());
    }

    /**
     * Get available status types.
     */
    public function getStatusTypes(): array
    {
        return JobShortListing::getStatusTypes();
    }

    /**
     * Get valid status transitions for a short listing.
     */
    public function getValidTransitions(int $shortListingId): array
    {
        $shortListing = $this->jobShortListingRepository->find($shortListingId);

        if (!$shortListing) {
            return [];
        }

        $validTransitions = [
            'interested' => ['shortlisted', 'withdraw', 'cancelled'],
            'shortlisted' => ['paid', 'withdraw', 'cancelled'],
            'paid' => ['accepted', 'withdraw', 'cancelled'],
            'accepted' => ['withdraw', 'cancelled'],
            'withdraw' => [],
            'cancelled' => [],
        ];

        return $validTransitions[$shortListing->status] ?? [];
    }
}
