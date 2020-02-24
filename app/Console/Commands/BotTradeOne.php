<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TradeRemoved;
use App\UserCurrency;
use App\UserHistory;
use App\UserTrades;
use App\User;

class BotTradeOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bottrade:one';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make bot 1 accept a trade';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function executeCommand()
    {
      $list_trade_bot = UserTrades::where('user_id',3)->where('status',1)->first();
      $bot_4_wallet = UserCurrency::where('user_id',4)->first();
      $bot_3_wallet = UserCurrency::where('user_id',3)->first();

      if($list_trade_bot != null){
          $bot4_bal = $list_trade_bot->request_currency;
          if($list_trade_bot->trade_amount > $bot_4_wallet->$bot4_bal){
              $this->line("bot4 is out of balance");
          }else{
              $value = $list_trade_bot->trade_currency;
              if($bot_3_wallet->$value >= $list_trade_bot->trade_amount){
                  $history =  new UserHistory;

                  $history->sender_id = 3;
                  $history->receiver_id = 4;
                  $history->amount = $list_trade_bot->trade_amount;
                  $history->transaction_option = "trade";
                  $history->currency_trade = $list_trade_bot->trade_currency;
                  $history->currency_request = $list_trade_bot->request_currency;

                  $bot_3_wallet->decrement($list_trade_bot->trade_currency,$list_trade_bot->trade_amount);
                  $bot_3_wallet->increment($list_trade_bot->request_currency,$list_trade_bot->request_amount);

                  $bot_4_wallet->decrement($list_trade_bot->request_currency,$list_trade_bot->request_amount);
                  $bot_4_wallet->increment($list_trade_bot->trade_currency,$list_trade_bot->trade_amount);

                  if($history->save()){
                      $list_trade_bot->status = 0;
                      $list_trade_bot->save();
                      $this->line('Sucess! bot3');
                      broadcast(new TradeRemoved($list_trade_bot->id));
                  }
              }else{
                  $this->line('bot3 is out of balance!');
              }
          }
      }else{
          $this->line('bot3 has no trade post');
      }
    }

    public function handle()
    {
        $this->executeCommand();
    }
}
