<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invites extends Model
{
    protected $fillable = [
        'message_id',
        'accepted',
    ];

    public function player_sent_to() {
        return $this->belongsTo('App\Models\Player', 'to_id');
    }

    public function dm_sent_from() {
        return $this->belongsTo('App\Models\DM', 'from_id');
    }

    public function message()
    {
        return $this->hasOne('App\Models\Message');
    }

    public function campaign()
    {
        return $this->hasOne('App\Models\Campaign');
    }
}
