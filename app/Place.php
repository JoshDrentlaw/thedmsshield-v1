<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public function campaign() {
        return $this->belongsTo('App\Campaign');
    }

    public function marker() {
        return $this->hasOne('App\Marker');
    }
}
