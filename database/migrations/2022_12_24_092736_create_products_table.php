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
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('status');
            $table->unsignedBigInteger('depot_id');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('cascade');
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('user_id')->on('agents')->onDelete('cascade');
            $table->unsignedBigInteger('factory_id');
            $table->foreign('factory_id')->references('user_id')->on('factories')->onDelete('cascade');
            $table->integer('warranty_count');
            $table->unsignedBigInteger('warranty_id');
            $table->foreign('warranty_id')->references('user_id')->on('warranties')->onDelete('cascade');
            $table->string('buy_time');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('user_id')->on('customers')->onDelete('cascade');
            $table->timestamps();
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
