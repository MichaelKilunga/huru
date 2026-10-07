<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_thread_id',
        'user_id',
        'content',
        'is_approved',
        'is_pinned',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_pinned' => 'boolean',
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
