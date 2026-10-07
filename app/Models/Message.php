<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public const DIRECTION_IN = 'inbound';
    public const DIRECTION_OUT = 'outbound';

    public const CHANNEL_SMS = 'sms';
    public const CHANNEL_WEB = 'web';

    protected $fillable = [
        'user_id',
        'direction',
        'channel',
        'content',
        'language',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiLog()
    {
        return $this->hasOne(AiLog::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
