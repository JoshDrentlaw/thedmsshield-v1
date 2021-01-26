<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Map;

class MapChatMessageChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user, $mapId)
    {
        $map = Map::find($mapId);
        $isDm = (int) $map->campaign->dm->id === (int) $user->id;
        $isPlayer = in_array($user->id, $map->campaign->active_player_ids);
        if ($isDm || $isPlayer) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar_public_id,
                'isDm' => $isDm,
                'isPlayer' => $isPlayer
            ];
        }
        return false;
    }
}