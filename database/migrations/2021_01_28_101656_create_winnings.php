<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winnings', function (Blueprint $table) {
            $table->increments('winning_id');
            $table->integer('quiz_group_id')->nullable();//->unsigned();
            $table->integer('contest_id')->nullable()->unsigned();
            $table->integer('app_user_id')->unsigned();
            $table->integer('position');
            $table->enum('rewarding_type',array('coin','cash'));
            $table->integer('amount_rewarded');
            $table->timestamp('created_at');

            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quiz_group_id')->references('group_id')->on('quiz_group')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('winnings',function(Blueprint $table){
            $table->dropForeign('winnings_app_user_id_foreign');           
            $table->dropForeign('winnings_quiz_group_id_foreign');           
            $table->dropForeign('winnings_contest_id_foreign');           
        });
        
        Schema::dropIfExists('winnings');
    }
}
