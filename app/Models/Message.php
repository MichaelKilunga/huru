<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'direction',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiLog()
    {
        return $this->hasOne(AiLog::class);
    }
}
