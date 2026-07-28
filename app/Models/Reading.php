<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    protected $fillable = ['uuid', 'user_id', 'question', 'cards', 'coincidences', 'numerology', 'mystic_message'];

    protected $casts = [
        'cards' => 'array',
        'coincidences' => 'array',
        'numerology' => 'array',
    ];
}