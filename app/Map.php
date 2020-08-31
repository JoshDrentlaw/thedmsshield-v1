<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    // Default table name would be maps since the Model is called Map
    // You can set it to whatever though
    // protected $table = 'whatever';

    // Primary Key change
    // default: id
    // public $primaryKey = 'map_id';

    // Disable timestamps
    // public $timestamps = false;

    public function campaign() {
        return $this->belongsTo('App\Campaign');
    }

    public function dm() {
        return $this->hasOneThrough('App\DM', 'App\Campaign');
    }

    public function players() {
        return $this->hasManyThrough('App\Player', 'App\Campaign');
    }

    public function markers() {
        return $this->hasMany('App\Marker');
    }

    protected static function booted()
    {
        parent::boot();
        static::deleted(function ($map) {
            $map->markers()->delete();
        });
    }
}
