<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Player;

class Map extends Model
{
    // Default table name would be maps since the Model is called Map
    // You can set it to whatever though
    // protected $table = 'whatever';

    // Primary Key change
    // default: id
    // public $primaryKey = 'map_id';

    // Disable timestamps
    // public $timestamps = false;

    public function dm() {
        return $this->belongsTo('App\DM', 'dm_id');
    }

    public function players() {
        return $this->belongsToMany('App\Player');
    }

    public function invites() {
        return $this->hasMany('App\Invites');
    }

    public function markers() {
        return $this->hasMany('App\Marker');
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

    protected static function booted()
    {
        parent::boot();
        static::deleted(function ($map) {
            $map->markers()->delete();
        });
    }
}
