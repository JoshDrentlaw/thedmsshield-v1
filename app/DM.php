<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DM extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function campaigns() {
        return $this->hasMany('App\Campaign', 'dm_id');
    }

    public function sent_invites() {
        return $this->hasMany('App\Invites', 'from_id');
    }

    public function maps() {
        return $this->hasManyThrough('App\Map', 'App\Campaign', null, 'dm_id');
    }

    public function markers() {
        return $this->hasManyThrough('App\Marker', 'App\Map');
    }
}
