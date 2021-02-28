<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creature extends Model
{
    protected $dateFormat = 'c';

    protected $fillable = [
        'name',
        'url',
        'body'
    ];

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