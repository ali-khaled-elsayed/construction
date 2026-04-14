<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_category_id',
        'code',
        'name',
        'type',
        'options',
        'is_required',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'job_category_id' => 'integer',
        'options' => 'array',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the job category that owns this attribute.
     */
    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    /**
     * Get the options for this attribute.
     */
    public function options()
    {
        return $this->hasMany(JobAttributeOption::class, 'job_attribute_id');
    }

    /**
     * Get the values for this attribute.
     */
    public function values()
    {
        return $this->hasMany(JobAttributeValue::class, 'job_attribute_id');
    }

    /**
     * Get the translations for this attribute.
     */
    public function translations()
    {
        return $this->hasMany(JobAttributeTranslation::class, 'job_attribute_id');
    }

    /**
     * Get translation for a specific language.
     */
    public function getTranslationFor(string $languageCode): ?JobAttributeTranslation
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
     * Scope to only required attributes.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Check if this attribute has options.
     */
    public function hasOptions(): bool
    {
        return !empty($this->options) && is_array($this->options);
    }

    /**
     * Get the available types for attributes.
     */
    public static function getTypes(): array
    {
        return [
            'text' => 'Text',
            'number' => 'Number',
            'boolean' => 'Boolean',
            'select' => 'Select (Single Choice)',
            'multi_select' => 'Multi Select (Multiple Choices)',
        ];
    }
}
