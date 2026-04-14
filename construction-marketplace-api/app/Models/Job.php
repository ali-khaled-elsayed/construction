<?php

namespace App\Models;

use App\Modules\Job\Enums\JobSize;
use App\Modules\Job\Enums\JobStatus;
use App\Modules\Job\Enums\Urgency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_items';

    protected $fillable = [
        'job_request_id',
        'category_id',
        'room_id',
        'fee_amount',
        'size',
        'description',
        'urgency',
        'status',
    ];

    protected $casts = [
        'fee_amount' => 'integer',
        'size' => JobSize::class,
        'urgency' => Urgency::class,
        'status' => JobStatus::class,
    ];

    public function jobRequest(): BelongsTo
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(JobHistory::class);
    }

    public function shortListings(): HasMany
    {
        return $this->hasMany(JobShortListing::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(JobAttributeValue::class);
    }

    public function changeStatus(JobStatus $newStatus, User $changedBy, ?string $reason = null): void
    {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) {
            return;
        }

        $this->update(['status' => $newStatus]);

        JobHistory::create([
            'job_id' => $this->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'reason' => $reason,
            'changed_by' => $changedBy->id,
        ]);
    }
}
