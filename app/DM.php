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

    public function maps() {
        return $this->hasMany('App\Map', 'dm_id');
    }

    public function markers() {
        return $this->hasManyThrough('App\Marker', 'App\Map');
    }
}
