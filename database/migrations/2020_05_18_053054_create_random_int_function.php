<?php

use Illuminate\Database\Migrations\Migration;

class CreateRandomIntFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION random_between(low INT ,high INT)
            RETURNS INT AS
            $$
            BEGIN
            RETURN floor(random()* (high-low + 1) + low);
            END;
            $$ language 'plpgsql' STRICT;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION `random_between`');
    }
}
