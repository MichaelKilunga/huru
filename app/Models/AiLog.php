<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    protected $fillable = [
        'message_id',
        'model',
        'prompt',
        'response',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
