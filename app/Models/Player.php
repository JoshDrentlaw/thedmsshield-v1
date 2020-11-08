<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function campaigns() {
        return $this->belongsToMany('App\Models\Campaign');
    }

    public function received_invites() {
        return $this->hasMany('App\Models\Invites', 'to_id');
    }
}
