<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get city translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CityTranslation::class);
    }

    /**
     * Get service provider profiles for this city.
     */
    public function serviceProviderProfiles(): HasMany
    {
        return $this->hasMany(ServiceProviderProfile::class);
    }

    /**
     * Get job requests for this city.
     */
    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class);
    }

    /**
     * Get translation for a specific language.
     */
    public function getTranslationFor(string $languageCode): ?CityTranslation
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
