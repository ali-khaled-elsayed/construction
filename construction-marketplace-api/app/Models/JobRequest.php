<?php

namespace App\Models;

use App\Modules\Job\Enums\DescriptionType;
use App\Modules\Job\Enums\JobType;
use App\Modules\Job\Enums\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'unit_type',
        'job_type',
        'service_type',
        'description_type',
        'city_id',
        'country_code',
        'address',
    ];

    protected $casts = [
        'job_type' => JobType::class,
        'service_type' => ServiceType::class,
        'description_type' => DescriptionType::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function basicDescription(): HasOne
    {
        return $this->hasOne(BasicDescription::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
