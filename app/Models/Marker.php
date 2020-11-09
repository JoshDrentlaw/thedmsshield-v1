<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $table = 'markers';

    protected $with = ['place'];

    public function map() {
        return $this->belongsTo('App\Models\Map');
    }

    public function place() {
        return $this->belongsTo('App\Models\Place');
    }
}
