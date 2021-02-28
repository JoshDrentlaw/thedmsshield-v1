<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $table = 'markers';

    protected $with = ['place', 'creature'];

    public function map() {
        return $this->belongsTo('App\Models\Map');
    }

    public function place() {
        return $this->belongsTo('App\Models\Place');
    }

    public function creature() {
        return $this->belongsTo('App\Models\Creature');
    }

    public function getPlaceIconsAttribute() {
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
            'archway',
            'house-user',
            'hospital-user'
        ];
    }

    public function getCreatureIconsAttribute() {
        return [
            'location-arrow',
            'search-location',
            'thumbtack',
            'street-view',
            'compass',
            'plane',
            'plane-departure',
            'plane-arrival',
            'map-pin',
            'globe',
            'users',
            'user-friends',
            'user',
            'user-slash',
            'users-cog',
            'user-times',
            'user-tie',
            'user-tag',
            'user-slash',
            'user-shield',
            'user-secret',
            'user-plus',
            'user-nurse',
            'user-ninja',
            'user-music',
            'user-minus',
            'user-md',
            'user-lock',
            'user-injured',
            'user-graduate',
            'user-cog',
            'user-edit',
            'user-clock',
            'user-circle',
            'user-check',
            'user-astronaut',
            'user-alt-slash'
        ];
    }

    public function getPlayerIconsAttribute() {
        return [
            'location-arrow',
            'search-location',
            'thumbtack',
            'street-view',
            'compass',
            'plane',
            'plane-departure',
            'plane-arrival',
            'map-pin',
            'globe',
            'users',
            'user-friends',
            'user',
            'user-slash',
            'users-cog',
            'user-times',
            'user-tie',
            'user-tag',
            'user-slash',
            'user-shield',
            'user-secret',
            'user-plus',
            'user-nurse',
            'user-ninja',
            'user-music',
            'user-minus',
            'user-md',
            'user-lock',
            'user-injured',
            'user-graduate',
            'user-cog',
            'user-edit',
            'user-clock',
            'user-circle',
            'user-check',
            'user-astronaut',
            'user-alt-slash'
        ];
    }
}