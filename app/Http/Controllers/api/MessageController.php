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

        $roomId = ChatRoom::where('room_id', ChatRoom::getRoomId($user->id, $friendId))->first()->id;

        $messages = ChatMessage::where('room_id', $roomId)->get();

        // $room = ChatRoom::getRoomId($user->id, $friendId);

        return response()->json([
            'messages' => $messages->toArray(),
            'friend' => User::find($friendId),
            'room' => $roomId,
            'status' => true,
        ], 200);
    }

    public function store(Request $request)
    {
        // dd($request);
        $senderId = auth('sanctum')->user()->id;
        $message = ChatMessage::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'text' => $request->message,
            'room_id' => $request->room_id,
        ]);

        // $room = $this->getRoom($senderId, $request->receiver_id);

        // broadcast(new MessageSent($message))->toOthers();
        // Broadcast::private('chat.'.$room->room_id)->send();
        // event(new MessageSent($message, $room));
        // broadcast(new MessageSent(Auth::user(), $message))->toOthers();
        broadcast(new MessageSent($message))->toOthers();

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

    public function getRoomId($user_id)
    {
        // $current_user_id = Auth::user()->id;
        try {
            $current_user_id = auth('sanctum')->user()->id;
            $room_id = ChatRoom::getRoomId($current_user_id, $user_id);
            
            if (!$room_id) {
                $room_id = 'room_' . uniqid();
                ChatRoom::create([
                    'room_id' => $room_id,
                    'user1' => $current_user_id,
                    'user2' => $user_id,
                ]);
            }
    
            return response()->json(['room_id' => $room_id]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }


}
