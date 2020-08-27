<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TradePosted;
use App\UserCurrency;
use App\UserTrades;
use App\BotTrade;
use App\BotInfo;

class BotPostThree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botpost:three';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make bot 3 post a trade';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

    public function executeCommand()
    {
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
          $this->line("Bot 3 Done");
      }
    }

    public function handle()
    {
        $this->executeCommand();
    }
}
