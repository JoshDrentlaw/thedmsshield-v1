<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $dateFormat = 'c';

    public function campaign() {
        return $this->belongsTo('App\Campaign');
    }

    public function marker() {
        return $this->hasOne('App\Marker');
    }
}
