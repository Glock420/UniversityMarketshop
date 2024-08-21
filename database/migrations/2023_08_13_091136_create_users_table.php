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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('org_name',100)->nullable();
            $table->string('email',50);
            $table->string('password',100);
            $table->string('prof_pic',50)->default('default_pics/default_prof_pic.jpg');
            $table->string('chat_link',70)->nullable();
            $table->string('type',15);
            $table->string('gcash_no',11)->nullable();
            $table->boolean('is_disabled')->default(false);
            $table->integer('warn_count')->default(0);
        });

        DB::table('users')->insert([
            'first_name' => 'Abe',
            'last_name' => 'Cadelina',
            'email' => 'admin@su.edu.ph',
            'password' => Hash::make('pass789'),
            'type' => 'ADMIN',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
