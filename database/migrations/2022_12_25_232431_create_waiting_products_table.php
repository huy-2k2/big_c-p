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
        Schema::create('waiting_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('user_id')->on('agents')->onDelete('cascade');
            $table->unsignedBigInteger('range_id');
            $table->foreign('range_id')->references('id')->on('ranges')->onDelete('cascade');  
            $table->unsignedBigInteger('transfer_batch');
            $table->boolean('status');
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
        Schema::dropIfExists('waiting_products');
    }
};
