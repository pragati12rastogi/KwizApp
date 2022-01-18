<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_reward', function (Blueprint $table) {
            $table->increments('contest_reward_id');
            $table->integer('contest_id')->unsigned();
            $table->integer('position');
            $table->integer('position_amount');
            $table->timestamps();
            $table->foreign('contest_id')->references('contest_id')->on('contest')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_reward', function(Blueprint $table)
        {
            $table->dropForeign('contest_reward_contest_id_foreign');        
        });
        Schema::dropIfExists('contest_reward');
    }
}
