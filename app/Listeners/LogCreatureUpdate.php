<?php

namespace App\Listeners;

use App\Events\CreatureUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCreatureUpdate implements ShouldQueue
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
     * @param  CreatureUpdate  $event
     * @return void
     */
    public function handle(CreatureUpdate $event)
    {
        //
    }
}