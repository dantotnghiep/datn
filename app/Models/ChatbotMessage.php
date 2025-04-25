<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'message', 'read_at', 'type'];

    protected $attributes = [
        'type' => 'default',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'user_id');
    }
    public function chat()
    {
        return $this->belongsTo(ChatbotUser::class, 'id');
    }

    protected $casts = [
        'read_at' => 'datetime',
    ];
}
