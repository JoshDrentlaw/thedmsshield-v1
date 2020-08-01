<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invites extends Model
{
    protected $fillable = [
        'message_id',
        'accepted',
    ];

    public function player_sent_to() {
        return $this->belongsTo('App\Player', 'to_id');
    }

    public function dm_sent_from() {
        return $this->belongsTo('App\DM', 'from_id');
    }

    public function message()
    {
        return $this->hasOne('App\Message');
    }

    public function map()
    {
        return $this->hasOne('App\Map');
    }
}
