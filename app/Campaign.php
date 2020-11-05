<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function dm() {
        return $this->belongsTo('App\DM', 'dm_id');
    }

    public function players() {
        return $this->hasMany('App\Player');
    }

    public function maps() {
        return $this->hasMany('App\Map');
    }

    public function places() {
        return $this->hasMany('App\Place');
    }

    public function things() {
        return $this->hasMany('App\Thing');
    }

    public function ideas() {
        return $this->hasMany('App\Idea');
    }

    public function creatures() {
        return $this->hasMany('App\Creature');
    }

    public function invites() {
        return $this->hasMany('App\Invites');
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
