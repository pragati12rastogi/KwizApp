<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmitAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submit_answer', function (Blueprint $table) {
            $table->increments('submit_id');
            $table->integer('app_user_id')->nullable()->unsigned();
            $table->integer('quiz_ques_id')->nullable()->unsigned();
            $table->integer('contest_ques_id')->nullable()->unsigned();
            $table->boolean('result');
            $table->string('answering_time')->nullable();
            $table->double('points')->nullable();
            $table->timestamp('created_at');

            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quiz_ques_id')->references('ques_id')->on('quiz_group_ques')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contest_ques_id')->references('question_id')->on('contest_question')->onUpdate('cascade')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submit_answer',function(Blueprint $table){
            $table->dropForeign('submit_answer_app_user_id_foreign');           
            $table->dropForeign('submit_answer_quiz_ques_id_foreign');           
            $table->dropForeign('submit_answer_contest_ques_id_foreign');           
        });
        Schema::dropIfExists('submit_answer');
    }
}
