<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeTranslation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_type_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_type_id',
        'language_code',
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'room_type_id' => 'integer',
    ];

    /**
     * Get the room type that owns this translation.
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    /**
     * Get the language for this translation.
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }
}
