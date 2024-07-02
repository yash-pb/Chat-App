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
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id', 'room_id');
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2');
    }

    public static function getRoomId($user1, $user2)
    {
        $room = ChatRoom::where(function($query) use ($user1, $user2) {
            $query->where('user1', $user1)->where('user2', $user2);
        })->orWhere(function($query) use ($user1, $user2) {
            $query->where('user1', $user2)->where('user2', $user1);
        })->first();
        if ($room) {
            return $room->room_id;
        }

        return null;
    }


}
