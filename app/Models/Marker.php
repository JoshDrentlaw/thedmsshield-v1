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

    public function getAllIconsAttribute() {
        return [
            'building',
            'warehouse',
            'torii-gate',
            'synagogue',
            'store',
            'store-alt',
            'school',
            'place-of-worship',
            'mosque',
            'monument',
            'landmark',
            'kaaba',
            'industry',
            'house-damage',
            'hotel',
            'hospital',
            'hospital-alt',
            'gopuram',
            'city',
            'church',
            'university',
            'home',
            'dungeon',
            'vihara',
            'igloo',
            'hospital-user',
            'clinic-medical',
            'campground',
            'archway'
        ];
    }
}