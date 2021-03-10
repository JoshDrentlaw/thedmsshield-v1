<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Campaign extends Model
{
    public function dm() {
        return $this->belongsTo('App\Models\DM', 'dm_id');
    }

    public function players() {
        return $this->belongsToMany('App\Models\Player');
    }

    public function maps() {
        return $this->hasMany('App\Models\Map');
    }

    public function places() {
        return $this->hasMany('App\Models\Place');
    }

    public function organizations() {
        return $this->hasMany('App\Models\Organization');
    }

    public function items() {
        return $this->hasMany('App\Models\Item');
    }

    public function creatures() {
        return $this->hasMany('App\Models\Creature');
    }

    public function invites() {
        return $this->hasMany('App\Models\Invites');
    }

    public function getIsDmAttribute()
    {
        return (int) $this->dm->id === (int) Auth::user()->id;
    }

    public function getIsPlayerAttribute()
    {
        return  in_array(Auth::user()->id, $this->active_player_ids);
    }

    public function getActivePlayersAttribute()
    {
        $pending = collect([]);
        foreach ($this->invites as $invite) {
            if ($invite->accepted == 1) {
                $pending->push($invite->player_sent_to);
            }
        }
        return $pending;
    }

    public function getActivePlayerIdsAttribute()
    {
        $ids = [];
        foreach ($this->active_players as $player) {
            $ids[] = $player->id;
        }
        return $ids;
    }

    public function getPendingPlayersAttribute()
    {
        $pending = collect([]);
        foreach ($this->invites as $invite) {
            if ($invite->accepted == 0) {
                $pending->push($invite->player_sent_to);
            }
        }
        return $pending;
    }
}