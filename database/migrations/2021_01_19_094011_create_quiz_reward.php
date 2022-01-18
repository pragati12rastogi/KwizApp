<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_reward', function (Blueprint $table) {
            $table->increments('quiz_reward_id');
            $table->integer('quiz_id')->unsigned();
            $table->integer('position');
            $table->integer('position_amount');
            $table->timestamps();
            $table->foreign('quiz_id')->references('group_id')->on('quiz_group')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_reward', function(Blueprint $table)
        {
            $table->dropForeign('quiz_reward_quiz_id_foreign');        
        });
        Schema::dropIfExists('quiz_reward');
    }
}
