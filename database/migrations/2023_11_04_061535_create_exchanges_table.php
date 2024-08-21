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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->bigIncrements('exchange_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('phone');
            $table->string('province');
            $table->string('city');
            $table->string('street_add');
            $table->string('postal');
            $table->string('proof_pic1');
            $table->string('proof_pic2')->nullable();
            $table->string('proof_pic3')->nullable();
            $table->date('date');
            $table->string('reason',100);
            $table->text('details',1000)->nullable();
            $table->string('status');

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
        Schema::dropIfExists('exchanges');
    }
};
