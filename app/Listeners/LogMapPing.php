<?php

namespace App\Listeners;

use App\Events\MapPinged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogMapPing implements ShouldQueue
{
    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @var string
     */
    public $queue = 'mapPings';

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
     * @param  MapPinged  $event
     * @return void
     */
    public function handle(MapPinged $event)
    {
        //
    }
}