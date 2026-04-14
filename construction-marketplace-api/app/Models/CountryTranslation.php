<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'language_code',
        'name',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code');
    }
}
