<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'city_id',
    ];

    /**
     * Get the capital city for this country.
     */
    public function capitalCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get country translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class, 'country_code');
    }

    /**
     * Get service provider profiles for this country.
     */
    public function serviceProviderProfiles(): HasMany
    {
        return $this->hasMany(ServiceProviderProfile::class, 'country_code');
    }

    /**
     * Get job requests for this country.
     */
    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class, 'country_code');
    }

    /**
     * Get translation for a specific language.
     */
    public function getTranslationFor(string $languageCode): ?CountryTranslation
    {
        return $this->translations->firstWhere('language_code', $languageCode);
    }

    /**
     * Get the translated name for a specific language.
     */
    public function getTranslatedName(string $languageCode): string
    {
        $translation = $this->getTranslationFor($languageCode);
        return $translation ? $translation->name : $this->name;
    }
}
