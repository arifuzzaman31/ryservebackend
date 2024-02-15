<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->integer('category_id');
            $table->foreignId('sub_category_id');
            $table->foreignId('colour_id')->nullable()->default(0);
            $table->foreignId('size_id')->nullable()->default(0);
            $table->foreignId('fabric_id')->nullable()->default(0);
            $table->foreignId('user_id')->comment = "customer_id";
            $table->string('item_sku')->default('SIMPLE ITEM')->comment = "Item Sku";
            $table->integer('quantity');
            $table->double('selling_price',8,4)->comment= "original BDT currency";
            $table->double('charge_selling_price',8,4)->comment= "converted currency";
            $table->double('vat_rate',8,4)->default(0);
            $table->double('charge_vat_rate',8,4)->default(0);
            $table->double('vat_amount',8,4)->default(0)->comment= "original BDT currency";
            $table->double('charge_vat_amount',8,4)->default(0)->comment= "converted currency";
            $table->double('buying_price',8,4);
            $table->double('total_buying_price',8,4);
            $table->double('total_selling_price',8,4)->comment= "original BDT currency";
            $table->double('total_charge_selling_price',8,4)->comment= "converted currency";
            $table->double('unit_discount')->default(0);
            $table->double('charge_unit_discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->double('charge_total_discount')->default(0);
            $table->tinyInteger('is_claim_refund')->default(0)->comment = "0 for No Claim, 1 for claimed";
            $table->date('refund_claim_date')->nullable();
            $table->tinyInteger('is_refunded')->default(0)->comment = "0 for refund not done, 1 for refund done, 2 for reject";
            $table->date('refund_date')->nullable();
            $table->longText('refund_claim_reason')->nullable();
            $table->longText('refund_reject_reason')->nullable();
            $table->longText('refund_info')->nullable();
            $table->double('total_fragile_amount',8, 4)->default(0);
            $table->double('charge_fragile_amount',8, 4)->default(0);
            $table->string('charged_currency')->default('BDT');
            $table->float('exchange_rate')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('order_details');
    }
}
