<?php

namespace App\Events;

use Google\Service\Dataflow\IntegerList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public function __construct(public string $message, public int $id)
    {

    }

   
    public function broadcastOn(): array
    {
        return ['chat'];
    }
    public function broadcastAs()
    {
        return 'mwssage';

    }
}