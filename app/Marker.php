<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $table = 'markers';

    protected $with = ['place'];

    public function map() {
        return $this->belongsTo('App\Map');
    }

    public function place() {
        return $this->belongsTo('App\Place');
    }
}
