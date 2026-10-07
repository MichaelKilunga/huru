<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    protected $fillable = [
        'message_id',
        'model',
        'category',
        'status',
        'prompt',
        'response',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'latency_ms',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
