<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('tracking_id')->nullable();
            $table->date('process_date')->nullable();
            $table->tinyInteger('process_state')->nullable();
            $table->string('process_value')->nullable();
            $table->date('on_delivery_date')->nullable();
            $table->tinyInteger('on_delivery_state')->nullable();
            $table->string('on_delivery_value')->nullable();
            $table->date('delivery_date')->nullable();
            $table->tinyInteger('delivery_state')->nullable();
            $table->string('delivery_value')->nullable();
            $table->string('delivered_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('deliveries');
    }
}
