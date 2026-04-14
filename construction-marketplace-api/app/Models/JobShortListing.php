<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobShortListing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'provider_id',
        'status',
        'fee_amount',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'job_id' => 'integer',
        'provider_id' => 'integer',
        'fee_amount' => 'decimal:2',
    ];

    /**
     * Get the job that owns this short listing.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * Get the provider that owns this short listing.
     */
    public function provider()
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }

    /**
     * Get the user who created this short listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Get the history records for this short listing.
     */
    public function history()
    {
        return $this->hasMany(JobShortListingHistory::class, 'short_listing_id');
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, int $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    /**
     * Scope to filter by provider.
     */
    public function scopeForProvider($query, int $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to order by creation date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get available status types.
     */
    public static function getStatusTypes(): array
    {
        return [
            'interested' => 'Interested',
            'shortlisted' => 'Shortlisted',
            'paid' => 'Paid',
            'withdraw' => 'Withdrawn',
            'cancelled' => 'Cancelled',
            'accepted' => 'Accepted',
        ];
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabel(): string
    {
        $statuses = self::getStatusTypes();
        return $statuses[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Check if this is a status change action.
     */
    public function canChangeStatusTo(string $newStatus): bool
    {
        $validTransitions = [
            'interested' => ['shortlisted', 'withdraw', 'cancelled'],
            'shortlisted' => ['paid', 'withdraw', 'cancelled'],
            'paid' => ['accepted', 'withdraw', 'cancelled'],
            'accepted' => ['withdraw', 'cancelled'],
            'withdraw' => [],
            'cancelled' => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    /**
     * Get formatted description.
     */
    public function getFormattedDescription(): string
    {
        if ($this->description) {
            return $this->description;
        }

        return $this->getStatusLabel();
    }
}
