<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalAidProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'district',
        'location',
        'email',
        'phone',
        'language',
    ];
}
