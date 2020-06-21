<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $table = 'markers';

    public function map() {
        return $this->belongsTo('App\Map');
    }
}