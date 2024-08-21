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
        Schema::create('stock_audits', function (Blueprint $table) {
            $table->bigIncrements('audit_id');
            $table->unsignedBigInteger('prod_id');
            $table->text('audit_trail',1000);
            $table->timestamp('date')->useCurrent();

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
        Schema::dropIfExists('stock_audits');
    }
};
