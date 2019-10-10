<?php

namespace App\Events;

use App\UserTrades;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExampleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // public $userTrades = [];
    public $userTrades;

   
    public function __construct($userTrades)
    {
        $this->userTrades = $userTrades;
        // $this->data = [
        //     'userTrades' => $userTrades,
        // ];
    }

    public function broadcastOn()
    {
        return new Channel('laravel_database_channelname'/* . $this->data['userTrades']*/);
    }

    public function broadcastAs()
    {
        return 'examplee';
    }
}
