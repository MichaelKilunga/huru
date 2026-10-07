<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A place-bound service: a legal aid provider, hospital, police station,
 * agricultural office, district council desk, ... tied to a region and
 * district so the engine can point citizens to help near them.
 */
class LocalResource extends Model
{
    public const CATEGORIES = [
        'legal_aid' => 'Legal aid provider',
        'health' => 'Health facility',
        'police' => 'Police / gender desk',
        'agriculture' => 'Agriculture office',
        'government' => 'Government office',
        'education' => 'Education office',
        'finance' => 'Financial service',
        'other' => 'Other',
    ];

    protected $fillable = [
        'name',
        'category',
        'region',
        'district',
        'ward',
        'location',
        'phone',
        'email',
        'source',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
