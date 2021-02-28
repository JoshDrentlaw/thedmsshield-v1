<?php

namespace App\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

use App\Events\NewMapChatMessage;
use App\Listeners\SendNewMapChatMessage;

use App\Events\MapPinged;
use App\Listeners\LogMapPing;

use App\Events\UserMapUpdate;
use App\Listeners\UserMapUpdated;

use App\Events\MarkerUpdate;
use App\Listeners\LogMarkerUpdate;

use App\Events\PlaceUpdate;
use App\Listeners\LogPlaceUpdate;

use App\Events\CreatureUpdate;
use App\Listeners\LogCreatureUpdate;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewMapChatMessage::class => [
            SendNewMapChatMessage::class,
        ],
        MapPinged::class => [
            LogMapPing::class
        ],
        UserMapUpdate::class => [
            UserMapUpdated::class
        ],
        MarkerUpdate::class => [
            LogMarkerUpdate::class
        ],
        PlaceUpdate::class => [
            LogPlaceUpdate::class
        ],
        CreatureUpdate::class => [
            LogCreatureUpdate::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}