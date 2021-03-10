<?php

namespace App\Listeners;

use App\Events\ItemUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogItemUpdate
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
     * @param  ItemUpdate  $event
     * @return void
     */
    public function handle(ItemUpdate $event)
    {
        //
    }
}
