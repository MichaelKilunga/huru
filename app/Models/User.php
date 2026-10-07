<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'preferred_language',
        'region',
        'is_banned',
        'abuse_count',
        'opted_out',
        'otp_hash',
        'otp_expires_at',
        'otp_attempts',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_hash',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'opted_out' => 'boolean',
            'abuse_count' => 'integer',
            'otp_attempts' => 'integer',
            'otp_expires_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function communityMemberships()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function joinedThreads()
    {
        return $this->belongsToMany(CommunityThread::class, 'community_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function createdThreads()
    {
        return $this->hasMany(CommunityThread::class, 'creator_id');
    }

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    /** Display name that never leaks the full phone number to other citizens. */
    public function displayName(): string
    {
        if ($this->name) {
            return $this->name;
        }
        $digits = preg_replace('/\D/', '', (string) $this->phone_number);

        return 'Mwananchi ' . substr($digits, -4);
    }
}
