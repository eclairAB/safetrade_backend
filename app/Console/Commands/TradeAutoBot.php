<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TradePosted;
use App\UserCurrency;
use App\UserTrades;
use App\User;

class TradeAutoBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate the autobot in trade post.';

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


    public function bot_validator()
    {
        foreach ($bot_wallets as $bot_wallet) {

            $wallet = $second_wallets[$second_index];
            $trades = new UserTrades;

            foreach (range(1, $bot_wallet->$wallet) as $number) {
                $possible_trade_amount = array(number_format($number,10));

                $test_index = array_rand($possible_trade_amount);

                $the_chosen_one = $possible_trade_amount[$test_index];
                if($the_chosen_one > $bot_wallet->$wallet){
                    $this->line('Insufficient Balance!');
                }else{
                    $trades->user_id = $bot_wallet->user_id;
                    $trades->request_amount = $the_chosen_one;
                    $trades->trade_amount = $the_chosen_one;
                    $trades->request_currency = $first_wallets[$first_index];
                    $trades->trade_currency = $second_wallets[$second_index];

                    $trades->save();

                    broadcast(new TradePosted($trades));
                    $this->line("done");
                }
            }
        }
    }



    public function handle()
    {
        $this->line('Getting started...');
        $minimum_amount = 1;

        $first_wallets = array("btc", "eth","xrp", "ltc", "bch");
        $second_wallets = array("eos", "bnb", "usdt", "bsv", "trx");

        $first_index = array_rand($first_wallets);
        $second_index = array_rand($second_wallets);

        $bot_wallets = UserCurrency::whereIn('user_id', [3,4,5,6,7])->get();
        $bot_active_trade = UserTrades::whereIn('user_id', [3,4,5,6,7])->where('status',1)->get();


        if($bot_active_trade->count() > 0){
            return $this->bot_validator();
        }else{
            
            foreach ($bot_wallets as $bot_wallet) {

                $wallet = $second_wallets[$second_index];
                $trades = new UserTrades;

                foreach (range(1, $bot_wallet->$wallet) as $number) {
                    $possible_trade_amount = array(number_format($number,10));

                    $test_index = array_rand($possible_trade_amount);

                    $the_chosen_one = $possible_trade_amount[$test_index];
                    if($the_chosen_one > $bot_wallet->$wallet){
                        $this->line('Insufficient Balance!');
                    }else{
                        $trades->user_id = $bot_wallet->user_id;
                        $trades->request_amount = $the_chosen_one;
                        $trades->trade_amount = $the_chosen_one;
                        $trades->request_currency = $first_wallets[$first_index];
                        $trades->trade_currency = $second_wallets[$second_index];

                        $trades->save();

                        broadcast(new TradePosted($trades));
                        $this->line("done");
                    }
                }
            }

        }
    }
}
