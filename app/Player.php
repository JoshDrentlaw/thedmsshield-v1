<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function campaigns() {
        return $this->belongsToMany('App\Campaign');
    }

    public function received_invites() {
        return $this->hasMany('App\Invites', 'to_id');
    }
}
