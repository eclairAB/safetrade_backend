<?php

namespace App\Listeners;

use App\Events\AssetPriceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAssetPriceNotification
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
     * @param  AssetPriceUpdated  $event
     * @return void
     */
    public function handle(AssetPriceUpdated $event)
    {
        //
    }
}
