<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_markets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_pin');
            $table->integer('trader_id');
            $table->string('trade_date');
            $table->string('trade_time');
            $table->string('currency_a');
            $table->string('currency_a_amount');
            $table->string('currency_b');
            $table->string('currency_b_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_markets');
    }
}
