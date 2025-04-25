<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotUser extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $casts = [
        'user_id' => 'string',
    ];
    protected $fillable = ['user_id', 'name', 'email','phone','note','status','chat_id'];
    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'chat_id','id');
    }
}
