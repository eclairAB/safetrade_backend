<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SetTimescaledb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        fwrite(STDERR, print_r(config('env'), true));
        if (config('app.env') != 'testing') {
            DB::statement("SELECT create_hypertable('user_bets', 'timestamp')");
            DB::statement(
                "SELECT create_hypertable('asset_price_histories', 'timestamp')"
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
