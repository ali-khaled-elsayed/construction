<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the translations for this job category.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(JobCategoryTranslation::class, 'job_category_id');
    }

    /**
     * Get the attributes for this job category.
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(JobAttribute::class, 'job_category_id');
    }

    /**
     * Get translation for a specific language.
     */
    public function getTranslationFor(string $languageCode): ?JobCategoryTranslation
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

    /**
     * Get the translated description for a specific language.
     */
    public function getTranslatedDescription(string $languageCode): ?string
    {
        $translation = $this->getTranslationFor($languageCode);
        return $translation ? $translation->description : $this->description;
    }

    /**
     * Scope to only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }
}
