<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\AssetPriceHistory;

class AssetPriceUpdated implements ShouldBroadCast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $assetPrice;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AssetPriceHistory $price)
    {
        $this->assetPrice = $price;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('asset.price');
    }
}
