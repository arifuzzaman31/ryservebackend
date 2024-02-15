<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('slug')->nullable();
            $table->string('campaign_title')->nullable();
            $table->string('campaign_banner_default')->nullable();
            $table->string('campaign_meta_image')->nullable();
            $table->string('campaign_banner_one')->nullable();
            $table->string('campaign_banner_two')->nullable();
            $table->date('campaign_start_date');
            $table->date('campaign_expire_date');
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
        Schema::dropIfExists('campaigns');
    }
}
