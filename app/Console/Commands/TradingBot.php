<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TradePosted;
use App\UserCurrency;
use App\UserTrades;
use App\BotTrade;
use App\BotInfo;

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

    public function bot_validator()
    {

    }

    function getPairings()
    {
      $pairings = [];
      $bot_trade = BotTrade::select(array('wallet_one', 'wallet_two'))->get();

      foreach ($bot_trade as $item)
      {
        array_push($pairings, $item->wallet_one . "," . $item->wallet_two);
      }
      return $pairings;
    }

    public function bot_first()
    {
        // Get the bot wallets
        $bot_wallets = UserCurrency::where('user_id', 3)->first();

        $arr = $this->getPairings();
        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        $selected_combination = BotTrade::where('wallet_one',$first)->where('wallet_two',$second)->first();

        $bot_id = rand(1,3);
        $bot_info = BotInfo::where('id',$bot_id)->first();

        $debit_crypto = rand($selected_combination->min_one * 1000000000, $selected_combination->max_one * 1000000000)/1000000000;
        $credit_crypto = rand($selected_combination->min_two * 1000000000, $selected_combination->max_two * 1000000000)/1000000000;

        if($credit_crypto > $bot_wallets->$second){
            $this->line('Insufficient Balance!');
        }else{
            $trades = new UserTrades;

            $trades->user_id = 3;
            $trades->request_amount = $debit_crypto;
            $trades->trade_amount = $credit_crypto;
            $trades->request_currency = $first;
            $trades->trade_currency = $second;
            $trades->bot_name = $bot_info->bot_name;
            $trades->bot_image = $bot_info->bot_image;

            $trades->save();
            broadcast(new TradePosted($trades));
            $this->line("Bot 1 Done");
        }
    }

    public function bot_second()
    {
        // Get the bot wallets
        $bot_wallets = UserCurrency::where('user_id', 4)->first();

        $arr = $this->getPairings();
        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        $selected_combination = BotTrade::where('wallet_one',$first)->where('wallet_two',$second)->first();

        $bot_id = rand(1,3);
        $bot_info = BotInfo::where('id',$bot_id)->first();

        $debit_crypto = rand($selected_combination->min_one * 1000000000, $selected_combination->max_one * 1000000000)/1000000000;
        $credit_crypto = rand($selected_combination->min_two * 1000000000, $selected_combination->max_two * 1000000000)/1000000000;

        if($credit_crypto > $bot_wallets->$second){
            $this->line('Insufficient Balance!');
        }else{
            $trades = new UserTrades;

            $trades->user_id = 4;
            $trades->request_amount = $debit_crypto;
            $trades->trade_amount = $credit_crypto;
            $trades->request_currency = $first;
            $trades->trade_currency = $second;
            $trades->bot_name = $bot_info->bot_name;
            $trades->bot_image = $bot_info->bot_image;

            $trades->save();
            broadcast(new TradePosted($trades));
            $this->line("Bot 2 Done");
        }
    }

    public function bot_third()
    {
        // Get the bot wallets
        $bot_wallets = UserCurrency::where('user_id', 5)->first();

        $arr = $this->getPairings();
        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        $selected_combination = BotTrade::where('wallet_one',$first)->where('wallet_two',$second)->first();

        $bot_id = rand(1,3);
        $bot_info = BotInfo::where('id',$bot_id)->first();

        $debit_crypto = rand($selected_combination->min_one * 1000000000, $selected_combination->max_one * 1000000000)/1000000000;
        $credit_crypto = rand($selected_combination->min_two * 1000000000, $selected_combination->max_two * 1000000000)/1000000000;

        if($credit_crypto > $bot_wallets->$second){
            $this->line('Insufficient Balance!');
        }else{
            $trades = new UserTrades;

            $trades->user_id = 5;
            $trades->request_amount = $debit_crypto;
            $trades->trade_amount = $credit_crypto;
            $trades->request_currency = $first;
            $trades->trade_currency = $second;
            $trades->bot_name = $bot_info->bot_name;
            $trades->bot_image = $bot_info->bot_image;

            $trades->save();
            broadcast(new TradePosted($trades));
            $this->line("Bot 3 Done");
        }
    }

    public function bot_fourth()
    {
        // Get the bot wallets
        $bot_wallets = UserCurrency::where('user_id', 6)->first();

        $arr = $this->getPairings();
        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        $selected_combination = BotTrade::where('wallet_one',$first)->where('wallet_two',$second)->first();

        $bot_id = rand(1,3);
        $bot_info = BotInfo::where('id',$bot_id)->first();

        $debit_crypto = rand($selected_combination->min_one * 1000000000, $selected_combination->max_one * 1000000000)/1000000000;
        $credit_crypto = rand($selected_combination->min_two * 1000000000, $selected_combination->max_two * 1000000000)/1000000000;

        if($credit_crypto > $bot_wallets->$second){
            $this->line('Insufficient Balance!');
        }else{
            $trades = new UserTrades;

            $trades->user_id = 6;
            $trades->request_amount = $debit_crypto;
            $trades->trade_amount = $credit_crypto;
            $trades->request_currency = $first;
            $trades->trade_currency = $second;
            $trades->bot_name = $bot_info->bot_name;
            $trades->bot_image = $bot_info->bot_image;

            $trades->save();
            broadcast(new TradePosted($trades));
            $this->line("Bot 4 Done");
        }
    }

    public function bot_fifth()
    {
        // Get the bot wallets
        $bot_wallets = UserCurrency::where('user_id', 7)->first();

        $arr = $this->getPairings();
        $trade_index = array_rand($arr);

        $combinations = explode(",", $arr[$trade_index]);
        $first = $combinations[0];
        $second = $combinations[1];

        $selected_combination = BotTrade::where('wallet_one',$first)->where('wallet_two',$second)->first();

        $bot_id = rand(1,3);
        $bot_info = BotInfo::where('id',$bot_id)->first();

        $debit_crypto = rand($selected_combination->min_one * 1000000000, $selected_combination->max_one * 1000000000)/1000000000;
        $credit_crypto = rand($selected_combination->min_two * 1000000000, $selected_combination->max_two * 1000000000)/1000000000;

        if($credit_crypto > $bot_wallets->$second){
            $this->line('Insufficient Balance!');
        }else{
            $trades = new UserTrades;

            $trades->user_id = 7;
            $trades->request_amount = $debit_crypto;
            $trades->trade_amount = $credit_crypto;
            $trades->request_currency = $first;
            $trades->trade_currency = $second;
            $trades->bot_name = $bot_info->bot_name;
            $trades->bot_image = $bot_info->bot_image;

            $trades->save();
            broadcast(new TradePosted($trades));
            $this->line("Bot 5 Done");
        }
    }

    public function handle()
    {
        // $this->bot_first();
        $list_trade_bots = UserCurrency::whereIn('user_id',[3,4,5,6,7])->get();
        foreach ($list_trade_bots as $list_trade_bot) {
            switch ($list_trade_bot->user_id) {
                case 3:
                    echo $this->bot_first();
                    break;
                case 4:
                    echo $this->bot_second();
                    break;
                case 5:
                    echo $this->bot_third();
                    break;
                case 6:
                    echo $this->bot_fourth();
                    break;
                case 7:
                    echo $this->bot_fifth();
                    break;
            }
        }
    }
}
