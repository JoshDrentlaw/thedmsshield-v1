<?php

namespace App\Events;

use App\Models\MapChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMapChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mapChatMessage;

    /**
     * The name of the queue connection to use when broadcasting the event.
     *
     * @var string
     */
    // public $connection = 'database';

    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @var string
     */
    public $queue = 'database';

    // public $afterCommit = true;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MapChatMessage $mapChatMessage)
    {
        $this->mapChatMessage = $mapChatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('map-chat-message-' . $this->mapChatMessage->map_id);
    }

    /* public function broadcastAs()
    {
        return 'new-map-chat-message';
    } */
}