<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->double('btc', 11, 3);
            $table->double('eth', 11, 3);
            $table->double('xrp', 11, 3);
            $table->double('ltc', 11, 3);
            $table->double('bch', 11, 3);
            $table->double('eos', 11, 3);
            $table->double('bnb', 11, 3);
            $table->double('usdt', 11, 3);
            $table->double('bsv', 11, 3);
            $table->double('trx', 11, 3);
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
        Schema::dropIfExists('user_currencies');
    }
}
