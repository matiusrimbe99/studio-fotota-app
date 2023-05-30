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
            $table->id();
            $table->string('code_order');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('packet_id');
            $table->unsignedBigInteger('studio_id');
            $table->date('shooting_date');
            $table->unsignedBigInteger('status_order_id');
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('packet_id')->references('id')->on('packets');
            $table->foreign('studio_id')->references('id')->on('studios');
            $table->foreign('status_order_id')->references('id')->on('status_orders');
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
