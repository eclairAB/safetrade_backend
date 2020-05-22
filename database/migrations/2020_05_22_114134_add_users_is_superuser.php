<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersIsSuperuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_staff')->default(false);
            $table->boolean('is_superuser')->default(false);
            $table->dropColumn('user_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_staff');
            $table->dropColumn('is_superuser');
            $table->string('user_level');
        });
    }
}
