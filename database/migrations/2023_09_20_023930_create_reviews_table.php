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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('rev_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('prod_id');
            $table->decimal('rate',2,1);
            $table->text('content',1000);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('reviews');
    }
};
