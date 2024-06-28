<?php

use App\Models\ChatRoom;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


// routes/channels.php

// Broadcast::channel('chat', function (Request $request, $user, $roomId) {
//   dd($request, $user, $roomId);
//   // if($roomId) {
//   //   // return Auth::check();
//   // }
//   return true;
//     // dd($user);
//   });


Broadcast::channel('chat-room.{roomId}', function (User $user, $roomId) {
  // return true;
  // Check if the user is part of the chat room
  $chatRoom = ChatRoom::where('room_id', $roomId)
              ->where(function ($query) use ($user) {
                  $query->where('user1', $user->id)
                        ->orWhere('user2', $user->id);
              })
              ->orWhere(function ($query) use ($user) {
                $query->where('user2', $user->id)
                      ->orWhere('user1', $user->id);
              })
              ->first();
  // dd($user, $chatRoom);
  return $chatRoom !== null;
});

