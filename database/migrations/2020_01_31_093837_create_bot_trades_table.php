<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_trades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wallet_one');
            $table->string('wallet_two');
            $table->string('min_one');
            $table->string('max_one');
            $table->string('min_two');
            $table->string('max_two');
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
        Schema::dropIfExists('bot_trades');
    }
}
