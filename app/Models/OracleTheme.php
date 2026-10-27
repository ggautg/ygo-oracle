<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OracleTheme extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'generation',
        'era',
        'icon',
        'color',
        'description',
        'keywords',
        'archetypes',
        'vibe',
    ];

    protected $casts = [
        'keywords' => 'array',
        'archetypes' => 'array',
        'generation' => 'integer',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(YgoCard::class, 'theme_id');
    }
}
