<?php

namespace App\Listeners;

use App\Events\UserMapUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserMapUpdated
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
     * @param  UserMapUpdate  $event
     * @return void
     */
    public function handle(UserMapUpdate $event)
    {
        //
    }
}
