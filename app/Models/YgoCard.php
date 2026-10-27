<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YgoCard extends Model
{
    protected $fillable = [
        'ygo_id',
        'name',
        'type',
        'frame_type',
        'race',
        'attribute',
        'level',
        'atk',
        'def',
        'description',
        'description_es',
        'banlist_status',
        'image_url',
        'archetype',
        'theme_id',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(OracleTheme::class, 'theme_id');
    }
}