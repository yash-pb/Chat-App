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


Broadcast::channel('chat-room.{roomId}', function (User $user, $friendId) {
  $chatRoom = ChatRoom::where(function ($query) use ($user, $friendId) {
                  $query->where('user1', $user->id)
                        ->orWhere('user2', $friendId);
              })
              ->orWhere(function ($query) use ($user, $friendId) {
                $query->where('user2', $user->id)
                      ->orWhere('user1', $friendId);
              })
              ->first();
              
  if ($user->chatRooms()->room_id == $chatRoom->room_id)
    return ['id' => $user->id, 'name' => $user->name];
});


Broadcast::channel('new-friend.{id}', function ($user, $userId) {
  // return true;
  // dd($user, $userId);
  return $user->id == $userId;
});