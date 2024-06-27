<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Models\ChatRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function friends() {
        // $message = ChatMessage::where('id', 1)->first();
        // $result = broadcast(new MessageSent($message))->toOthers();
        // dd($result);
        $user = auth('sanctum')->user();        
        $friends = $user->friends();

        return response()->json([
            'friends' => $friends,
            'status' => true,
        ], 200);
    }
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $friendId = $request->friend_id;
        // Retrieve messages between the authenticated user and their friend
        $messages = ChatMessage::where(function ($query) use ($user, $friendId) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $friendId);
        })
        ->orWhere(function ($query) use ($user, $friendId) {
            $query->where('sender_id', $friendId)
                ->where('receiver_id', $user->id);
        })
        // ->orderBy('created_at')
        ->get();
        
        // Optionally, eager load sender and receiver details
        // $messages->load('sender_id', 'receiver_id');

        // $groupedMessages = $messages->groupBy(function($message) {
        //     return Carbon::parse($message->created_at)->format('Y-m-d');
        // })->toArray();
        // dd($groupedMessages);
        // dd($messages->toArray());
        $room = $this->getRoom($user->id, $friendId);
        return response()->json([
            'messages' => $messages->toArray(),
            'friend' => User::find($friendId),
            'room' => $room,
            'status' => true,
        ], 200);
    }

    public function store(Request $request)
    {
        $senderId = auth('sanctum')->user()->id;
        $message = ChatMessage::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'text' => $request->message,
        ]);

        $room = $this->getRoom($senderId, $request->receiver_id);

        // broadcast(new MessageSent($message))->toOthers();
        event(new MessageSent($message, $room));
        // Broadcast::private('chat.'.$room->room_id)->send();

        return ['status' => 'Message Sent!'];
    }

    public function getRoom($user1, $user2) {
        $room = ChatRoom::where([
            ['user1', $user1],
            ['user2', $user2]
    
            ])->orWhere([
                ['user1', $user2],
                ['user2', $user1]
            ])->first();

        if($room == null){
            $room = ChatRoom::create([
                'room_id'=> str()->random(10),
                'user1'=> $user1,
                'user2'=> $user2
            ]);
        }

        return $room;
    }

}
