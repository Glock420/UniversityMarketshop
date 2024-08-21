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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('cat_id');
            $table->string('cat_name',30);
        });

        DB::table('categories')->insert([
            ['cat_name' => 'Shirt'],
            ['cat_name' => 'Jacket'],
            ['cat_name' => 'Bag'],
            ['cat_name' => 'Keychain'],
            ['cat_name' => 'Pin'],
            ['cat_name' => 'Lanyard'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
