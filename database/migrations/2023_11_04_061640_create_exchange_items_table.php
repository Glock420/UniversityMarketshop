<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_items', function (Blueprint $table) {
            $table->bigIncrements('exchangeitem_id');
            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('prod_id');
            $table->string('prod_name');
            $table->string('prod_image');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('quantity');

            $table->foreign('exchange_id')->references('exchange_id')->on('exchanges')->onDelete('restrict');
            $table->foreign('prod_id')->references('prod_id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_items');
    }
};
