<?php

namespace App\Listeners;

use App\Events\OrganizationUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrganizationUpdate
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
     * @param  OrganizationUpdate  $event
     * @return void
     */
    public function handle(OrganizationUpdate $event)
    {
        //
    }
}
