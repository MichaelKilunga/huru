<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_thread_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(CommunityThread::class, 'community_thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
