<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttributeOptionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id',
        'language_code',
        'name',
    ];

    public function option()
    {
        return $this->belongsTo(JobAttributeOption::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code');
    }
}
