<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('asset_id');
            $table->timestampTz('timestamp');
            $table->decimal('amount', 11, 3);

            // The amount the user will gain/lose
            $table
                ->decimal('gain', 11, 3)
                ->nullable()
                ->default(null);

            // We use boolean to determine bet to store smallest byte possible
            // true if user bets it will go up, false if it will go down
            $table->boolean('will_go_up');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('asset_id')
                ->references('id')
                ->on('assets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bets');
    }
}
