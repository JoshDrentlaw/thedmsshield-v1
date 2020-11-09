<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DM extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function campaigns() {
        return $this->hasMany('App\Models\Campaign', 'dm_id');
    }

    public function sent_invites() {
        return $this->hasMany('App\Models\Invites', 'from_id');
    }

    public function maps() {
        return $this->hasManyThrough('App\Models\Map', 'App\Models\Campaign', null, 'dm_id');
    }

    public function markers() {
        return $this->hasManyThrough('App\Models\Marker', 'App\Models\Map');
    }
}
