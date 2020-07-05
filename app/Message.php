<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function user_sent_to() {
        return $this->belongsTo('App\User', 'to_id');
    }

    public function user_sent_from() {
        return $this->belongsTo('App\User', 'from_id');
    }
}
