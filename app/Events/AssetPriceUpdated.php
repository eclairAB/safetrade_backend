<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\UserBet;

class AssetPriceUpdated implements ShouldBroadCast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $assetId,
        $timestamp,
        $price,
        UserBet $userBet = null
    ) {
        $this->data = [
            'asset_id' => $assetId,
            'timestamp' => $timestamp,
            'price' => $price,
            'betData' => null,
        ];
        if ($userBet) {
            $this->data['betData'] = [
                'up' => $userBet->will_go_up,
                'amount' => $userBet->amount,
                'timestamp' => strtotime($userBet->timestamp),
                'user_id' => $userBet->user_id,
                'avatar_url' => $userBet->user->user_display_pic
            ];
        }
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
