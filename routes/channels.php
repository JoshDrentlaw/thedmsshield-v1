<?php

use App\Models\MapChatMessage;
use App\Models\Map;
use App\Debug\Debug;
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\CampaignMapChannel;

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

Broadcast::channel('campaign-map-{mapId}', CampaignMapChannel::class);