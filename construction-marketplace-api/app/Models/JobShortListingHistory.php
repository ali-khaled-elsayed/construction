<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobShortListingHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'short_listing_id',
        'user_id',
        'old_status',
        'new_status',
        'description',
        'changes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'short_listing_id' => 'integer',
        'user_id' => 'integer',
        'changes' => 'array',
    ];

    /**
     * Get the short listing that owns this history record.
     */
    public function shortListing()
    {
        return $this->belongsTo(JobShortListing::class, 'short_listing_id');
    }

    /**
     * Get the user who performed this action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to filter by short listing.
     */
    public function scopeForShortListing($query, int $shortListingId)
    {
        return $query->where('short_listing_id', $shortListingId);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by old status.
     */
    public function scopeByOldStatus($query, string $oldStatus)
    {
        return $query->where('old_status', $oldStatus);
    }

    /**
     * Scope to filter by new status.
     */
    public function scopeByNewStatus($query, string $newStatus)
    {
        return $query->where('new_status', $newStatus);
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
    public function getOldStatusLabel(): string
    {
        $statuses = self::getStatusTypes();
        return $statuses[$this->old_status] ?? ucfirst(str_replace('_', ' ', $this->old_status));
    }

    /**
     * Get human-readable status label.
     */
    public function getNewStatusLabel(): string
    {
        $statuses = self::getStatusTypes();
        return $statuses[$this->new_status] ?? ucfirst(str_replace('_', ' ', $this->new_status));
    }

    /**
     * Check if this is a status change action.
     */
    public function isStatusChange(): bool
    {
        return $this->old_status !== $this->new_status;
    }

    /**
     * Get formatted description.
     */
    public function getFormattedDescription(): string
    {
        if ($this->description) {
            return $this->description;
        }

        if ($this->isStatusChange()) {
            return "Status changed from '{$this->getOldStatusLabel()}' to '{$this->getNewStatusLabel()}'";
        }

        return $this->getNewStatusLabel();
    }
}
