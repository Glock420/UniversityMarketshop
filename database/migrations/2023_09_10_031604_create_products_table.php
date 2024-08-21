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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('prod_id');
            $table->unsignedBigInteger('user_id');
            $table->string('category',30);
            $table->string('prod_name',40);
            $table->decimal('price',10,2);
            $table->integer('quantity')->nullable();
            $table->text('description',1000);
            $table->text('reject_reason',1000)->nullable();
            $table->string('image1',50)->default('default_pics/default_prod_pic.jpg');
            $table->string('image2',50)->nullable();
            $table->string('image3',50)->nullable();
            $table->string('image4',50)->nullable();
            $table->string('image5',50)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->string('prod_status',10)->default('ENABLED');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
