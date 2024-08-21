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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('cartitem_id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('prod_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('prod_name',40);
            $table->string('prod_image',50);
            $table->string('color',30)->nullable();
            $table->string('size',30)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('subtotal',10,2)->nullable();

            $table->foreign('prod_id')->references('prod_id')->on('products')->onDelete('restrict');
            $table->foreign('cart_id')->references('cart_id')->on('carts')->onDelete('restrict');
            $table->foreign('seller_id')->references('user_id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
