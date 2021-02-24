<?php

namespace App\Listeners;

use App\Events\PlaceUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogPlaceUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PlaceUpdate  $event
     * @return void
     */
    public function handle(PlaceUpdate $event)
    {
        //
    }
}