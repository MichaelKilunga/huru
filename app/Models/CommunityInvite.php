<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommunityInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_thread_id',
        'inviter_id',
        'invitee_id',
        'email',
        'token',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invite) {
            $invite->token = Str::random(32);
        });
    }

    public function thread()
    {
        return $this->belongsTo(CommunityThread::class, 'community_thread_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
