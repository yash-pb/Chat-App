<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Events\NewFriend;
use App\Models\ChatRoom;
use App\Models\User;

class MessageController extends Controller
{

    public function friends() {
        $user = auth('sanctum')->user();        
        $friends = $user->friends();

        return response()->json([
            'friends' => $friends,
            'status' => true,
        ], 200);
    }
    public function index(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $friendId = $request->friend_id;
            $roomId = null;
            $messages = [];

            $roomId = ChatRoom::getRoomId($user->id, $friendId);
            if(!empty($roomId)) {
                $messages = ChatMessage::where('room_id', $roomId)->get()->toArray();
            }

            return response()->json([
                'messages' => $messages,
                'friend' => User::find($friendId),
                'room' => $roomId,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function store(Request $request)
    {
        $room_id = $request->room_id;
        $senderId = auth('sanctum')->user()->id;
        $firstMsg = false;
        if(!ChatRoom::where('room_id', $room_id)->exists()) {
            ChatRoom::create([
                'room_id' => $room_id,
                'user1' => $senderId,
                'user2' => $request->receiver_id,
            ]);
            $firstMsg = true;
        }

        $message = ChatMessage::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'text' => $request->message,
            'room_id' => $room_id,
        ]);
        event(new NewFriend($message, $firstMsg));
        event(new MessageSent($message));

        return ['status' => true, 'message' => $message, 'first_msg' => $firstMsg];
    }

    public function searchUser(Request $request) {
        $users = [];
        if($request->search != null || $request->search != '')
            $users = User::where('name', 'LIKE', '%'. $request->search .'%')->where('id', '!=', auth('sanctum')->user()->id)->get();
        return response()->json(['users' => $users, 'status' => true]);
    }

}
