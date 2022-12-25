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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->change();
            $table->unsignedBigInteger('warranty_id')->nullable()->change();
            $table->unsignedBigInteger('batch_id')->nullable()->change();
            $table->unsignedBigInteger('depot_id')->nullable()->change();
            $table->unsignedBigInteger('warranty_count')->nullable()->change();
            $table->unsignedBigInteger('buy_time')->nullable()->change();
            $table->unsignedBigInteger('customer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable(false)->change();
            $table->unsignedBigInteger('warranty_id')->nullable(false)->change();
            $table->unsignedBigInteger('batch_id')->nullable(false)->change();
            $table->unsignedBigInteger('depot_id')->nullable(false)->change();
            $table->unsignedBigInteger('warranty_count')->nullable(false)->change();
            $table->unsignedBigInteger('buy_time')->nullable(false)->change();
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
};
