<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameAndImageFieldIntoUserTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_trades', function (Blueprint $table) {
            $table->string('bot_name')->after('status')->nullable();
            $table->longText('bot_image')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_trades', function (Blueprint $table) {
            //
        });
    }
}
