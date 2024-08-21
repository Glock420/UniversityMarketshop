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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('orderitem_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('prod_id');
            $table->string('prod_name');
            $table->string('prod_image');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal',8,2);

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('restrict');
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
        Schema::dropIfExists('order_items');
    }
};
