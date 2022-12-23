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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('quantity');
            $table->string('date');
            $table->unsignedBigInteger('range_id');
            $table->foreign('range_id')->references('id')->on('ranges')->onDelete('cascade');
            $table->unsignedBigInteger('factory_id');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
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
        Schema::dropIfExists('batches');
    }
};
