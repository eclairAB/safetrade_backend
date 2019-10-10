<?php

namespace App\Events;

use Log;
use App\UserCurrency;
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

    public $data = [];

   
    public function __construct(UserCurrency $userCurrency)
    {
       // $this->payload = $payload;
        $this->data = [
            'userCurrency' => $userCurrency,
        ];
        Log::info('hey something just happened')
    }

    public function broadcastOn()
    {
        Log::info('hey something just happened')
        return new Channel('laravel_database_channelname' . $this->data['userCurrency']);
    }

    public function broadcastAs()
    {
        Log::info('hey something just happened')
        return 'examplee';
    }
}
