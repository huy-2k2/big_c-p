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
        Schema::create('warranty_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('batch_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->boolean('status'); //true -> sửa xong
            $table->text('reason');
            $table->unsignedBigInteger('product_error_id');
            $table->foreign('product_error_id')->references('id')->on('product_errors')->onDelete('cascade');
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
        Schema::dropIfExists('warranty_products');
    }
};
