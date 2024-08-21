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
        Schema::create('ship_fees', function (Blueprint $table) {
            $table->bigIncrements('fee_id');
            $table->decimal('fee',5,2);
        });

        DB::table('ship_fees')->insert([
            'fee' => 120,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ship_fees');
    }
};