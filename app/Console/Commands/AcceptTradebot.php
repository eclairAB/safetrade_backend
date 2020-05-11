<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TradeRemoved;
use App\UserCurrency;
use App\UserHistory;
use App\UserTrades;
use App\User;

class AcceptTradebot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accept:trade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accept the trade bot.';

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

    // TRADE ROUTINE;
    // user4 -> user5
    // user5 -> user6
    // user6 -> user7
    // user7 -> user3

    public function bot_3_to_4()
    {
        $list_trade_bot = UserTrades::where('user_id', 3)
            ->where('status', 1)
            ->first();
        $bot_4_wallet = UserCurrency::where('user_id', 4)->first();
        $bot_3_wallet = UserCurrency::where('user_id', 3)->first();

        if ($list_trade_bot != null) {
            $bot4_bal = $list_trade_bot->request_currency;
            if ($list_trade_bot->trade_amount > $bot_4_wallet->$bot4_bal) {
                $this->line("bot4 is out of balance");
            } else {
                $value = $list_trade_bot->trade_currency;
                if ($bot_3_wallet->$value >= $list_trade_bot->trade_amount) {
                    $history = new UserHistory();

                    $history->sender_id = 3;
                    $history->receiver_id = 4;
                    $history->amount = $list_trade_bot->trade_amount;
                    $history->transaction_option = "trade";
                    $history->currency_trade = $list_trade_bot->trade_currency;
                    $history->currency_request =
                        $list_trade_bot->request_currency;

                    $bot_3_wallet->decrement(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );
                    $bot_3_wallet->increment(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );

                    $bot_4_wallet->decrement(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );
                    $bot_4_wallet->increment(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );

                    if ($history->save()) {
                        $list_trade_bot->status = 0;
                        $list_trade_bot->save();
                        $this->line('Sucess! bot3');
                        broadcast(new TradeRemoved($list_trade_bot->id));
                    }
                } else {
                    $this->line('bot3 is out of balance!');
                }
            }
        } else {
            $this->line('bot3 has no trade post');
        }
    }

    public function bot_4_to_5()
    {
        $list_trade_bot = UserTrades::where('user_id', 4)
            ->where('status', 1)
            ->first();
        $bot_5_wallet = UserCurrency::where('user_id', 5)->first();
        $bot_4_wallet = UserCurrency::where('user_id', 4)->first();

        if ($list_trade_bot != null) {
            $bot5_bal = $list_trade_bot->request_currency;
            if ($list_trade_bot->trade_amount > $bot_5_wallet->$bot5_bal) {
                $this->line("bot5 is out of balance");
            } else {
                $value = $list_trade_bot->trade_currency;
                if ($bot_4_wallet->$value >= $list_trade_bot->trade_amount) {
                    $history = new UserHistory();

                    $history->sender_id = 4;
                    $history->receiver_id = 5;
                    $history->amount = $list_trade_bot->trade_amount;
                    $history->transaction_option = "trade";
                    $history->currency_trade = $list_trade_bot->trade_currency;
                    $history->currency_request =
                        $list_trade_bot->request_currency;

                    $bot_4_wallet->decrement(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );
                    $bot_4_wallet->increment(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );

                    $bot_5_wallet->decrement(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );
                    $bot_5_wallet->increment(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );

                    if ($history->save()) {
                        $list_trade_bot->status = 0;
                        $list_trade_bot->save();
                        $this->line('Sucess! bot4');
                        broadcast(new TradeRemoved($list_trade_bot->id));
                    }
                } else {
                    $this->line('bot4 is out of balance!');
                }
            }
        } else {
            $this->line('bot4 has no trade post');
        }
    }

    public function bot_5_to_6()
    {
        $list_trade_bot = UserTrades::where('user_id', 5)
            ->where('status', 1)
            ->first();
        $bot_6_wallet = UserCurrency::where('user_id', 6)->first();
        $bot_5_wallet = UserCurrency::where('user_id', 5)->first();

        if ($list_trade_bot != null) {
            $bot6_bal = $list_trade_bot->request_currency;
            if ($list_trade_bot->trade_amount > $bot_6_wallet->$bot6_bal) {
                $this->line("bot6 is out of balance");
            } else {
                $value = $list_trade_bot->trade_currency;
                if ($bot_5_wallet->$value >= $list_trade_bot->trade_amount) {
                    $history = new UserHistory();

                    $history->sender_id = 5;
                    $history->receiver_id = 6;
                    $history->amount = $list_trade_bot->trade_amount;
                    $history->transaction_option = "trade";
                    $history->currency_trade = $list_trade_bot->trade_currency;
                    $history->currency_request =
                        $list_trade_bot->request_currency;

                    $bot_5_wallet->decrement(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );
                    $bot_5_wallet->increment(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );

                    $bot_6_wallet->decrement(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );
                    $bot_6_wallet->increment(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );

                    if ($history->save()) {
                        $list_trade_bot->status = 0;
                        $list_trade_bot->save();
                        $this->line('Sucess! bot5');
                        broadcast(new TradeRemoved($list_trade_bot->id));
                    }
                } else {
                    $this->line('bot5 is out of balance!');
                }
            }
        } else {
            $this->line('bot5 has no trade post');
        }
    }

    public function bot_6_to_7()
    {
        $list_trade_bot = UserTrades::where('user_id', 6)
            ->where('status', 1)
            ->first();
        $bot_7_wallet = UserCurrency::where('user_id', 7)->first();
        $bot_6_wallet = UserCurrency::where('user_id', 6)->first();

        if ($list_trade_bot != null) {
            $bot7_bal = $list_trade_bot->request_currency;
            if ($list_trade_bot->trade_amount > $bot_7_wallet->$bot7_bal) {
                $this->line("bot7 is out of balance");
            } else {
                $value = $list_trade_bot->trade_currency;
                if ($bot_6_wallet->$value >= $list_trade_bot->trade_amount) {
                    $history = new UserHistory();

                    $history->sender_id = 6;
                    $history->receiver_id = 7;
                    $history->amount = $list_trade_bot->trade_amount;
                    $history->transaction_option = "trade";
                    $history->currency_trade = $list_trade_bot->trade_currency;
                    $history->currency_request =
                        $list_trade_bot->request_currency;

                    $bot_6_wallet->decrement(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );
                    $bot_6_wallet->increment(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );

                    $bot_7_wallet->decrement(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );
                    $bot_7_wallet->increment(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );

                    if ($history->save()) {
                        $list_trade_bot->status = 0;
                        $list_trade_bot->save();
                        $this->line('Sucess! bot6');
                        broadcast(new TradeRemoved($list_trade_bot->id));
                    }
                } else {
                    $this->line('bot6 is out of balance!');
                }
            }
        } else {
            $this->line('bot6 has no trade post');
        }
    }

    public function bot_7_to_3()
    {
        $list_trade_bot = UserTrades::where('user_id', 7)
            ->where('status', 1)
            ->first();
        $bot_3_wallet = UserCurrency::where('user_id', 3)->first();
        $bot_7_wallet = UserCurrency::where('user_id', 7)->first();

        if ($list_trade_bot != null) {
            $bot3_bal = $list_trade_bot->request_currency;
            if ($list_trade_bot->trade_amount > $bot_3_wallet->$bot3_bal) {
                $this->line("bot3 is out of balance");
            } else {
                $value = $list_trade_bot->trade_currency;
                if ($bot_7_wallet->$value >= $list_trade_bot->trade_amount) {
                    $history = new UserHistory();

                    $history->sender_id = 7;
                    $history->receiver_id = 3;
                    $history->amount = $list_trade_bot->trade_amount;
                    $history->transaction_option = "trade";
                    $history->currency_trade = $list_trade_bot->trade_currency;
                    $history->currency_request =
                        $list_trade_bot->request_currency;

                    $bot_7_wallet->decrement(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );
                    $bot_7_wallet->increment(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );

                    $bot_3_wallet->decrement(
                        $list_trade_bot->request_currency,
                        $list_trade_bot->request_amount
                    );
                    $bot_3_wallet->increment(
                        $list_trade_bot->trade_currency,
                        $list_trade_bot->trade_amount
                    );

                    if ($history->save()) {
                        $list_trade_bot->status = 0;
                        $list_trade_bot->save();
                        $this->line('Sucess! bot7');
                        broadcast(new TradeRemoved($list_trade_bot->id));
                    }
                } else {
                    $this->line('bot7 is out of balance!');
                }
            }
        } else {
            $this->line('bot7 has no trade post');
        }
    }

    public function handle()
    {
        $list_trade_bots = UserTrades::whereIn('user_id', [
            3,
            4,
            5,
            6,
            7,
        ])->get();
        foreach ($list_trade_bots as $list_trade_bot) {
            switch ($list_trade_bot->user_id) {
                case 3:
                    echo $this->bot_3_to_4();
                    break;
                case 4:
                    echo $this->bot_4_to_5();
                    break;
                case 5:
                    echo $this->bot_5_to_6();
                    break;
                case 6:
                    echo $this->bot_6_to_7();
                    break;
                case 7:
                    echo $this->bot_7_to_3();
                    break;
            }
        }
    }
}
