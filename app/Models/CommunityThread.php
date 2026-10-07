<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'creator_id',
        'is_private',
        'is_system',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    public function invites()
    {
        return $this->hasMany(CommunityInvite::class);
    }
}
