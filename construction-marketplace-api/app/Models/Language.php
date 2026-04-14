<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
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
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get country translations for this language.
     */
    public function countryTranslations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class, 'language_code');
    }

    /**
     * Get city translations for this language.
     */
    public function cityTranslations(): HasMany
    {
        return $this->hasMany(CityTranslation::class, 'language_code');
    }

    /**
     * Get room type translations for this language.
     */
    public function roomTypeTranslations(): HasMany
    {
        return $this->hasMany(RoomTypeTranslation::class, 'language_code');
    }

    /**
     * Get job category translations for this language.
     */
    public function jobCategoryTranslations(): HasMany
    {
        return $this->hasMany(JobCategoryTranslation::class, 'language_code');
    }

    /**
     * Get job attribute translations for this language.
     */
    public function jobAttributeTranslations(): HasMany
    {
        return $this->hasMany(JobAttributeTranslation::class, 'language_code');
    }

    /**
     * Get job attribute option translations for this language.
     */
    public function jobAttributeOptionTranslations(): HasMany
    {
        return $this->hasMany(JobAttributeOptionTranslation::class, 'language_code');
    }
}
