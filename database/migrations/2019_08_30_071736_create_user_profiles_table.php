<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_display_pic')->nullable();
            $table->string('user_level');
            $table->string('user_name');
            $table->string('user_password');
            $table->string('email');
            $table->string('name_first');
            $table->string('name_last');
            $table->string('contact_no');
            $table->string('birth_date');
            $table->string('zip_code');
            $table->string('city');
            $table->string('address');
            $table->string('country');
            $table->string('state');
            $table->string('transaction_pin')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
