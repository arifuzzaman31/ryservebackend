<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('transaction_id');
            $table->double('amount',8,2);
            $table->double('charge_amount',8,2)->comment = "its converted amount by changing currency";
            $table->string('charged_currency')->default('BDT');
            $table->float('exchange_rate')->nullable();
            $table->string('payment_type')->nullable();
            $table->tinyInteger('payment_status')->nullable();
            $table->tinyInteger('is_refunded')->default(0)->comment = "0 for refund not done, 1 for refund done, 2 for reject";
            $table->date('refund_date')->nullable();
            $table->longText('refund_info')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
