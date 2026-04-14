<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'image_url',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }
}
