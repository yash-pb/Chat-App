<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'sender_id',
        'receiver_id',
        'text'
    ];

    // public function sender_id()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    // public function receiver_id()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }

    public function sender_id() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver_id() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the chat room that owns the message.
     */
    public function chatRoom() {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

}
