<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function sender_message()
    // {
    //     return $this->hasMany(ChatMessage::class, 'sender_id');
    // }

    // public function receiver_message()
    // {
    //     return $this->hasMany(ChatMessage::class, 'receiver_id');
    // }

    // public function friends()
    // {
    //     return $this->hasManyThrough(User::class, ChatMessage::class, 'sender_id', 'id', 'id', 'receiver_id')
    //         ->where('sender_id', $this->id)
    //         ->orWhere('receiver_id', $this->id)
    //         ->distinct();
    // }
    // public function friends()
    // {
    //     $userId = $this->id;

    //     // Get distinct friend IDs where the current user is either sender or receiver
    //     $friendIds = ChatMessage::select(DB::raw('DISTINCT CASE WHEN sender_id = '.$userId.' THEN receiver_id ELSE sender_id END as friend_id', [$userId]))
    //         ->where('sender_id', $userId)
    //         ->orWhere('receiver_id', $userId)
    //         ->pluck('friend_id');

    //     // Return the User models for the friend IDs
    //     return User::whereIn('id', $friendIds)->get()->toArray();
    // }

    public function friends()
    {
        $userId = $this->id;

        // Get distinct friend IDs where the current user is either sender or receiver
        $friendIds = ChatRoom::select(DB::raw('DISTINCT CASE WHEN user1 = '.$userId.' THEN user2 ELSE user1 END as friend_id', [$userId]))
            ->where('user1', $userId)
            ->orWhere('user2', $userId)
            ->pluck('friend_id');

        // Return the User models for the friend IDs
        return User::whereIn('id', $friendIds)->get()->toArray();
    }

    // public function messages()
    // {
    //     return $this->hasMany(ChatMessage::class, 'sender_id')->orWhere('receiver_id', $this->id);
    // }

    /**
     * Get the chat rooms for the user.
     */
    public function chatRooms() {
        return $this->belongsToMany(ChatRoom::class, 'user1');
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages() {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages() {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

}
