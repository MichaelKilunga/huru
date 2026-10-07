<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Persona paragraph for a (category, language) pair. The engine appends
 * context and constraints itself, so a template only describes who the
 * assistant is and how it should behave for that topic.
 */
class PromptTemplate extends Model
{
    protected $fillable = [
        'name',
        'category',
        'language',
        'template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        $bust = fn () => Cache::forget('huru.templates.active');
        static::saved($bust);
        static::deleted($bust);
    }
}
