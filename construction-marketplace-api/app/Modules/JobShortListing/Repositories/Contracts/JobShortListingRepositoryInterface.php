<?php

namespace App\Modules\JobShortListing\Repositories\Contracts;

use App\Models\JobShortListing;
use App\Models\JobShortListingHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface JobShortListingRepositoryInterface
{
    /**
     * Get all short listings for a specific job.
     */
    public function getByJobId(int $jobId): Collection;

    /**
     * Get short listings by provider.
     */
    public function getByProviderId(int $providerId): Collection;

    /**
     * Get short listings by status.
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get short listings with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new short listing.
     */
    public function create(array $data): JobShortListing;

    /**
     * Update a short listing.
     */
    public function update(int $id, array $data): JobShortListing;

    /**
     * Delete a short listing.
     */
    public function delete(int $id): bool;

    /**
     * Get short listing by ID.
     */
    public function find(int $id): ?JobShortListing;

    /**
     * Get short listing by job and provider.
     */
    public function findByJobAndProvider(int $jobId, int $providerId): ?JobShortListing;

    /**
     * Get short listing history.
     */
    public function getHistory(int $shortListingId): Collection;

    /**
     * Get short listing history with pagination.
     */
    public function getHistoryWithPagination(int $shortListingId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create short listing history record.
     */
    public function createHistory(array $data): JobShortListingHistory;

    /**
     * Get short listing status changes.
     */
    public function getStatusChanges(int $shortListingId): Collection;

    /**
     * Get the latest history record for a short listing.
     */
    public function getLatestHistory(int $shortListingId): ?JobShortListingHistory;

    /**
     * Get short listings that need attention (e.g., pending payments).
     */
    public function getPendingAttention(): Collection;
}
