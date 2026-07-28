<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'banlist_status',
        'image_url',
    ];
}