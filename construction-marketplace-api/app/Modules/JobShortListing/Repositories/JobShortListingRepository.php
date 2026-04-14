<?php

namespace App\Modules\JobShortListing\Repositories;

use App\Models\JobShortListing;
use App\Models\JobShortListingHistory;
use App\Modules\JobShortListing\Repositories\Contracts\JobShortListingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobShortListingRepository implements JobShortListingRepositoryInterface
{
    /**
     * Get all short listings for a specific job.
     */
    public function getByJobId(int $jobId): Collection
    {
        return JobShortListing::with(['provider', 'user'])
            ->forJob($jobId)
            ->ordered()
            ->get();
    }

    /**
     * Get short listings by provider.
     */
    public function getByProviderId(int $providerId): Collection
    {
        return JobShortListing::with(['job', 'user'])
            ->forProvider($providerId)
            ->ordered()
            ->get();
    }

    /**
     * Get short listings by status.
     */
    public function getByStatus(string $status): Collection
    {
        return JobShortListing::with(['job', 'provider', 'user'])
            ->byStatus($status)
            ->ordered()
            ->get();
    }

    /**
     * Get short listings with pagination.
     */
    public function getByJobIdWithPagination(int $jobId, int $perPage = 15): LengthAwarePaginator
    {
        return JobShortListing::with(['provider', 'user'])
            ->forJob($jobId)
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Create a new short listing.
     */
    public function create(array $data): JobShortListing
    {
        return JobShortListing::create($data);
    }

    /**
     * Update a short listing.
     */
    public function update(int $id, array $data): JobShortListing
    {
        $shortListing = $this->find($id);

        if (!$shortListing) {
            throw new \Exception('Short listing not found');
        }

        $shortListing->update($data);
        return $shortListing->fresh();
    }

    /**
     * Delete a short listing.
     */
    public function delete(int $id): bool
    {
        $shortListing = $this->find($id);

        if (!$shortListing) {
            return false;
        }

        return $shortListing->delete();
    }

    /**
     * Get short listing by ID.
     */
    public function find(int $id): ?JobShortListing
    {
        return JobShortListing::with(['job', 'provider', 'user'])->find($id);
    }

    /**
     * Get short listing by job and provider.
     */
    public function findByJobAndProvider(int $jobId, int $providerId): ?JobShortListing
    {
        return JobShortListing::where('job_id', $jobId)
            ->where('provider_id', $providerId)
            ->first();
    }

    /**
     * Get short listing history.
     */
    public function getHistory(int $shortListingId): Collection
    {
        return JobShortListingHistory::with(['user'])
            ->forShortListing($shortListingId)
            ->ordered()
            ->get();
    }

    /**
     * Get short listing history with pagination.
     */
    public function getHistoryWithPagination(int $shortListingId, int $perPage = 15): LengthAwarePaginator
    {
        return JobShortListingHistory::with(['user'])
            ->forShortListing($shortListingId)
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Create short listing history record.
     */
    public function createHistory(array $data): JobShortListingHistory
    {
        return JobShortListingHistory::create($data);
    }

    /**
     * Get short listing status changes.
     */
    public function getStatusChanges(int $shortListingId): Collection
    {
        return JobShortListingHistory::with(['user'])
            ->forShortListing($shortListingId)
            ->byOldStatus('interested')
            ->byNewStatus('shortlisted')
            ->orWhere(function ($query) {
                $query->byOldStatus('shortlisted')
                    ->byNewStatus('paid');
            })
            ->orWhere(function ($query) {
                $query->byOldStatus('paid')
                    ->byNewStatus('accepted');
            })
            ->ordered()
            ->get();
    }

    /**
     * Get the latest history record for a short listing.
     */
    public function getLatestHistory(int $shortListingId): ?JobShortListingHistory
    {
        return JobShortListingHistory::with(['user'])
            ->forShortListing($shortListingId)
            ->ordered()
            ->first();
    }

    /**
     * Get short listings that need attention (e.g., pending payments).
     */
    public function getPendingAttention(): Collection
    {
        return JobShortListing::with(['job', 'provider', 'user'])
            ->byStatus('shortlisted')
            ->orWhere('status', 'paid')
            ->ordered()
            ->get();
    }
}
