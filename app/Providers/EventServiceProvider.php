<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        'App\Events\TradePosted' => ['App\Listeners\TradePostedListener'],
        'App\Events\TradeRemoved' => ['App\Listeners\TradeRemovedListener'],
        'App\Events\WalletUpdated' => ['App\Listeners\WalletUpdatedListener'],
        'App\Events\AssetPriceUpdated' => [
            'App\Listeners\SendAssetPriceNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
