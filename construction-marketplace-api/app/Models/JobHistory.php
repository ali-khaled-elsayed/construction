<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'user_id',
        'action',
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
        'job_id' => 'integer',
        'user_id' => 'integer',
        'changes' => 'array',
    ];

    /**
     * Get the job that owns this history record.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * Get the user who performed this action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, int $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    /**
     * Scope to filter by action.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to order by creation date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get available action types.
     */
    public static function getActionTypes(): array
    {
        return [
            'created' => 'Job Created',
            'updated' => 'Job Updated',
            'status_changed' => 'Status Changed',
            'assigned' => 'Job Assigned',
            'provider_accepted' => 'Provider Accepted',
            'provider_rejected' => 'Provider Rejected',
            'completed' => 'Job Completed',
            'cancelled' => 'Job Cancelled',
            'rescheduled' => 'Job Rescheduled',
            'payment_made' => 'Payment Made',
            'payment_received' => 'Payment Received',
            'review_added' => 'Review Added',
            'note_added' => 'Note Added',
        ];
    }

    /**
     * Get human-readable action label.
     */
    public function getActionLabel(): string
    {
        $actions = self::getActionTypes();
        return $actions[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    /**
     * Check if this is a status change action.
     */
    public function isStatusChange(): bool
    {
        return $this->action === 'status_changed' && $this->old_status && $this->new_status;
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
            return "Status changed from '{$this->old_status}' to '{$this->new_status}'";
        }

        return $this->getActionLabel();
    }
}
