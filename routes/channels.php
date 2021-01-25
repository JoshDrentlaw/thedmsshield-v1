<?php

use App\Models\MapChatMessage;
use App\Models\Map;
use App\Models\Debug;
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\MapChatMessageChannel;

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/* Broadcast::channel('map-chat-message-{mapId}', function ($user, $mapId) {
    $map = Map::find($mapId);
    $isDm = (int) $map->campaign->dm->id === (int) $user->id;
    $isPlayer = in_array($user->id, $map->campaign->active_player_ids);
    return $isDm || $isPlayer;
    // return false;
}); */

Broadcast::channel('map-chat-message-{mapId}', MapChatMessageChannel::class);