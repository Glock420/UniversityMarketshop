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
        Schema::create('variations', function (Blueprint $table) {
            $table->bigIncrements('variation_id');
            $table->unsignedBigInteger('prod_id');
            $table->string('color',30)->nullable();
            $table->string('size',30)->nullable();
            $table->integer('quantity')->nullable();

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
        Schema::dropIfExists('variations');
    }
};
