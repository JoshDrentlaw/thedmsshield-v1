<?php

namespace App\Jobs;

use App\Models\MapChatMessage;
use App\Events\NewMapChatMessage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMapChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The podcast instance.
     *
     * @var \App\Models\MapChatMessage
     */
    protected $mapChatMessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MapChatMessage $mapChatMessage)
    {
        $this->mapChatMessage = $mapChatMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}