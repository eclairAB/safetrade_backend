<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetPriceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_price_histories', function (Blueprint $table) {
            $table->timestampTz('timestamp');
            $table->unsignedTinyInteger('asset_id');
            $table->decimal('price', 11, 3);

            $table->primary(['asset_id', 'timestamp']);
            $table
                ->foreign('asset_id')
                ->references('id')
                ->on('assets')
                ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_price_histories');
    }
}
