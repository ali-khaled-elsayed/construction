<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttributeValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_attribute_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'job_attribute_id' => 'integer',
    ];

    /**
     * Get the job attribute that owns this value.
     */
    public function jobAttribute()
    {
        return $this->belongsTo(JobAttribute::class, 'job_attribute_id');
    }
}
