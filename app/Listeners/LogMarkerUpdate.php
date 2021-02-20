<?php

namespace App\Listeners;

use App\Events\MarkerUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogMarkerUpdate implements ShouldQueue
{
    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @var string
     */
    public $queue = 'markerUpdates';

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
     * @param  MarkerUpdate  $event
     * @return void
     */
    public function handle(MarkerUpdate $event)
    {
        //
    }
}