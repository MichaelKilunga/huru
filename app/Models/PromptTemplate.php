<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptTemplate extends Model
{
    protected $fillable = [
        'name',
        'template',
        'temperature',
        'max_tokens',
        'tone',
        'language',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'temperature' => 'float',
        'max_tokens' => 'integer',
    ];
}
