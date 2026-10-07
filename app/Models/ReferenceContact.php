<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * National directory entry: an institution, hotline or office that applies
 * country-wide (police 112, TRA, NIDA, NHIF, Legal aid campaign, ...).
 * Editable by admins so the engine never relies on hard-coded facts that
 * go stale.
 */
class ReferenceContact extends Model
{
    protected $fillable = [
        'name',
        'category',
        'phone',
        'alt_phone',
        'email',
        'website',
        'description_sw',
        'description_en',
        'is_emergency',
        'is_active',
        'verified_at',
        'sort_order',
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        $bust = fn () => Cache::forget('huru.contacts.active');
        static::saved($bust);
        static::deleted($bust);
    }

    public function description(string $language): string
    {
        return $language === 'en'
            ? ($this->description_en ?: (string) $this->description_sw)
            : ($this->description_sw ?: (string) $this->description_en);
    }
}
