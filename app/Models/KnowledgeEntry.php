<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * A curated fact block about life in Tanzania (law, health, farming,
 * government services, money, education, ...). Retrieved by keyword overlap
 * and injected into the prompt so answers are grounded in local reality.
 */
class KnowledgeEntry extends Model
{
    protected $fillable = [
        'title',
        'content',
        'summary',
        'category',
        'keywords',
        'language',
        'source',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        $bust = fn () => Cache::forget('huru.knowledge.active');
        static::saved($bust);
        static::deleted($bust);
    }
}
