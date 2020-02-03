<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TradingBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trading:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function test()
    {
        //
    }
    public function handle()
    {
        $arr = [
            'btc,eth',
            'btc,xrp',

            'eth,btc',
            'eth,xrp',

            'xrp,btc',
            'xrp,eth'
        ];

        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        
    }
}
