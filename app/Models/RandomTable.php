<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'table_data',
        'campaign_id'
    ];

    public function campaign() {
        return $this->belongsTo('App\Models\Campaign');
    }
}