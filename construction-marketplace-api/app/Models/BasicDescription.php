<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class BasicDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_request_id',
        'rooms_count',
        'wet_rooms_count',
        'external_rooms_count',
        'has_garden',
        'has_roof',
        'area',
        'description',
    ];

    protected $casts = [
        'has_garden' => 'boolean',
        'has_roof' => 'boolean',
        'area' => 'decimal:2',
    ];

    public function jobRequest(): BelongsTo
    {
        return $this->belongsTo(JobRequest::class);
    }
}
