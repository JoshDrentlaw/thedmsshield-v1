<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'map_request_id',
    ];

    public function user_sent_to() {
        return $this->belongsTo('App\User', 'to_id');
    }

    public function user_sent_from() {
        return $this->belongsTo('App\User', 'from_id');
    }

    public function invite()
    {
        return $this->belongsTo('App\Invites');
    }
}
