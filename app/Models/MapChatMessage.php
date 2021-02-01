<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapChatMessage extends Model
{
    use HasFactory;

    protected $dateFormat = 'c';

    protected $fillable = [
        'message',
        'map_id',
        'user_id'
    ];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }
}