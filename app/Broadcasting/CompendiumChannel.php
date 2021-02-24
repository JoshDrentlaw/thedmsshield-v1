<?php

namespace App\Broadcasting;


use App\Models\User;
use App\Models\Campaign;

class CompendiumChannel
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
    public function join(User $user, $campaignId)
    {
        $campaign = Campaign::find($campaignId);
        $isDm = $campaign->isDm;
        $isPlayer = $campaign->isPlayer;
        return $isDm || $isPlayer;
        /* if ($isDm || $isPlayer) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar_public_id,
                'isDm' => $isDm,
                'isPlayer' => $isPlayer
            ];
        } */
    }
}