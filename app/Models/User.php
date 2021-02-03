<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'map_color',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function dm() {
        return $this->hasOne('App\Models\DM');
    }

    public function player() {
        return $this->hasOne('App\Models\Player');
    }

    public function sent_messages() {
        return $this->hasMany('App\Models\Message', 'from_id');
    }

    public function received_messages() {
        return $this->hasMany('App\Models\Message', 'to_id');
    }

    public function map_chat_messages() {
        return $this->hasMany(MapChatMessage::class);
    }
}