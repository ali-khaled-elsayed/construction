<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    /**
     * Get the service provider profile for the user.
     */
    public function serviceProviderProfile(): HasOne
    {
        return $this->hasOne(ServiceProviderProfile::class);
    }

    /**
     * Get job requests where the user is the customer.
     */
    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class, 'customer_id');
    }

    /**
     * Get jobs where the user is assigned as a provider.
     */
    public function assignedJobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_short_listing', 'provider_id', 'job_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get short listings for this user as a provider.
     */
    public function shortListings(): HasMany
    {
        return $this->hasMany(JobShortListing::class, 'provider_id');
    }

    /**
     * Get comments made by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get ratings given by this user.
     */
    public function ratingsGiven(): HasMany
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /**
     * Get ratings received by this user.
     */
    public function ratingsReceived(): HasMany
    {
        return $this->hasMany(Rating::class, 'rated_id');
    }

    /**
     * Get job history entries created by this user.
     */
    public function jobHistoryChanges(): HasMany
    {
        return $this->hasMany(JobHistory::class, 'changed_by');
    }

    /**
     * Get short listing history entries created by this user.
     */
    public function shortListingHistoryChanges(): HasMany
    {
        return $this->hasMany(JobShortListingHistory::class, 'changed_by');
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === UserRole::CUSTOMER;
    }

    /**
     * Check if user is a service provider.
     */
    public function isServiceProvider(): bool
    {
        return $this->role === UserRole::SERVICE_PROVIDER;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Get the average rating for this user.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratingsReceived->avg('rating') ?? 0;
    }

    /**
     * Get the total number of ratings for this user.
     */
    public function getTotalRatingsAttribute(): int
    {
        return $this->ratingsReceived->count();
    }
}
