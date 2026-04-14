<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'rating',
        'city_id',
        'country_code',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'provider_id');
    }

    public function shortListings(): HasMany
    {
        return $this->hasMany(JobShortListing::class, 'provider_id');
    }

    public function ratingsReceived(): HasManyThrough
    {
        return $this->hasManyThrough(Rating::class, User::class, 'id', 'rated_id', 'user_id');
    }
}
