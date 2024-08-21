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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('phone');
            $table->string('province');
            $table->string('city');
            $table->string('street_add');
            $table->string('postal');
            $table->date('date');
            $table->date('receive_date')->nullable();
            $table->decimal('ship_fee',8,2);
            $table->string('ref_no')->nullable();
            $table->string('track_num')->nullable();
            $table->string('status');
            $table->boolean('has_exchange')->default(false);
            $table->text('cancel_reason',1000)->nullable();
            $table->decimal('total',8,2)->nullable();

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
        Schema::dropIfExists('orders');
    }
};
