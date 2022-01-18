<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchAdBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watch_ad_bonus', function (Blueprint $table) {
            $table->increments('watch_ad_bonus_id');
            $table->double('bonus_amount')->default(0);
            $table->bigInteger('updated_by')->unsigned();
            $table->timestamp('last_updated_at');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_bonus',function(Blueprint $table){
            $table->dropForeign('watch_ad_bonus_updated_by_foreign');           
        });
        Schema::dropIfExists('watch_ad_bonus');
    }
}
