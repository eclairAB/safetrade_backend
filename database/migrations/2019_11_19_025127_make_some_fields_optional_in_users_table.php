<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeSomeFieldsOptionalInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_first')->default('')->change();
            $table->string('name_last')->default('')->change();
            $table->string('contact_no')->default('')->change();
            $table->string('birth_date')->default('')->change();
            $table->string('zip_code')->default('')->change();
            $table->string('city')->default('')->change();
            $table->string('address')->default('')->change();
            $table->string('country')->default('')->change();
            $table->string('state')->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_first')->change();
            $table->string('name_last')->change();
            $table->string('contact_no')->change();
            $table->string('birth_date')->change();
            $table->string('zip_code')->change();
            $table->string('city')->change();
            $table->string('address')->change();
            $table->string('country')->change();
            $table->string('state')->change();
        });
    }
}
