<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\TradeAutoBot::class,
        \App\Console\Commands\AcceptTradebot::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $ts = date('Y-m-d-H-i-s');
        // $schedule->command('trading:bot')->cron('* * * * *')->sendOutputTo(storage_path('logs/trading-bot-'.$ts.'.log'));
        // $schedule->command('accept:trade')->cron('*/2 * * * *')->sendOutputTo(storage_path('logs/accept-trade-'.$ts.'.log'));

        $schedule
            ->command('botpost:one')
            ->cron('1 * * * *')
            ->sendOutputTo(storage_path('logs/botpostone-' . $ts . '.log'));
        $schedule
            ->command('bottrade:one')
            ->cron('2 * * * *')
            ->sendOutputTo(storage_path('logs/bottradeone-' . $ts . '.log'));

        $schedule
            ->command('botpost:two')
            ->cron('2 * * * *')
            ->sendOutputTo(storage_path('logs/botposttwo-' . $ts . '.log'));
        $schedule
            ->command('bottrade:two')
            ->cron('3 * * * *')
            ->sendOutputTo(storage_path('logs/bottradetwo-' . $ts . '.log'));

        $schedule
            ->command('botpost:three')
            ->cron('3 * * * *')
            ->sendOutputTo(storage_path('logs/botpostthree-' . $ts . '.log'));
        $schedule
            ->command('bottrade:three')
            ->cron('4 * * * *')
            ->sendOutputTo(storage_path('logs/bottradethree-' . $ts . '.log'));

        $schedule
            ->command('botpost:four')
            ->cron('4 * * * *')
            ->sendOutputTo(storage_path('logs/botpostfour-' . $ts . '.log'));
        $schedule
            ->command('bottrade:four')
            ->cron('5 * * * *')
            ->sendOutputTo(storage_path('logs/bottradefour-' . $ts . '.log'));

        $schedule
            ->command('botpost:five')
            ->cron('5 * * * *')
            ->sendOutputTo(storage_path('logs/botpostfive-' . $ts . '.log'));
        $schedule
            ->command('bottrade:five')
            ->cron('6 * * * *')
            ->sendOutputTo(storage_path('logs/bottradefive-' . $ts . '.log'));

        $schedule
            ->command('generate:random-bets cash')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/random-bets-' . $ts . '.log'));

        $schedule
            ->command('asset:compute-price cash')
            ->name('compute-price-cash')
            ->withoutOverlapping()
            ->everyMinute()
            ->appendOutputTo(
                storage_path('logs/compute-price-cash-' . $ts . '.log')
            );
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
