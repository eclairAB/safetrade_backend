<?php

namespace App\Listeners;

use App\Events\TradeRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TradeRemovedListener
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
     * @param  TradeRemoved  $event
     * @return void
     */
    public function handle(TradeRemoved $event)
    {
        //
    }
}
