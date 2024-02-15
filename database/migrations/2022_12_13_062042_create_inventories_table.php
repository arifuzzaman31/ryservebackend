<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreignId('colour_id');
            $table->foreignId('size_id');
            $table->integer('stock');
            $table->double('cpu',8,6)->default(0);
            $table->double('mrp',8,6)->default(0);
            $table->string('sku')->nullable();
            $table->integer('warning_amount')->default(10);
            $table->tinyInteger('disc_status')->default(0);
            $table->string('warehouse')->nullable();
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
        Schema::dropIfExists('inventories');
    }
}
