<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    protected $table = 'chat_room';
    protected $fillable = [
        'room_id',
        'user1',
        'user2',
    ];

    // public function sender_id()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    // public function receiver_id()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }
}
