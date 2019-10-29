<?php

namespace App\Listeners;

use App\Events\WalletUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WalletUpdatedListener
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
     * @param  WalletUpdated  $event
     * @return void
     */
    public function handle(WalletUpdated $event)
    {
        //
    }
}
