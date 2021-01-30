<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'status',
        'lat',
        'lng',
        'map_id',
    ];
}