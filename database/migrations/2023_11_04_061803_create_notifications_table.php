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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('notify_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->text('content',1000);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_report')->default(false);
            $table->boolean('is_warn')->default(false);
            $table->unsignedBigInteger('reported_user')->nullable();
            $table->string('reported_name',50)->nullable();
            $table->string('quick_link',70)->nullable();

            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};