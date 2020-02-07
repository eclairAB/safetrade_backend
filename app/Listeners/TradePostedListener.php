<?php

namespace App\Listeners;

use App\Events\TradePosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TradePostedListener
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
     * @param  TradePosted  $event
     * @return void
     */
    public function handle(TradePosted $event)
    {
        //
    }
}
