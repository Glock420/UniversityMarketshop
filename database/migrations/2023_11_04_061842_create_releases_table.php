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
        Schema::create('releases', function (Blueprint $table) {
            $table->bigIncrements('release_id');
            $table->unsignedBigInteger('orderitem_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('prod_name');
            $table->date('date_sent');

            $table->foreign('orderitem_id')->references('orderitem_id')->on('order_items')->onDelete('restrict');
            $table->foreign('buyer_id')->references('user_id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('releases');
    }
};
