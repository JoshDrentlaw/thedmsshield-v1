<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $dateFormat = 'c';

    public function campaign() {
        return $this->belongsTo('App\Models\Campaign');
    }

    public function marker() {
        return $this->hasOne('App\Models\Marker');
    }

    public function getMarkerlessAttribute()
    {
        return !$this->marker ? true : false;
    }
}