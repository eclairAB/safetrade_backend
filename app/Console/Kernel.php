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
        $schedule->command('trade:bot')->cron('*/10 * * * *')->sendOutputTo(storage_path('logs/trade-bot-'.$ts.'.log'));
        $schedule->command('accept:trade')->cron('*/1 * * * *')->sendOutputTo(storage_path('logs/accept-trade-'.$ts.'.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
