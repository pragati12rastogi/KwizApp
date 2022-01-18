<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizCategoryQues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_group_ques', function (Blueprint $table) {
            $table->increments('ques_id');
            $table->integer('quiz_qroup_id')->unsigned();
            $table->text('question');
            $table->text('option1');
            $table->text('option2');
            $table->text('option3');
            $table->text('option4');
            $table->string('answer');
            $table->integer('question_point');
            $table->string('question_time');
            $table->integer('status_id')->default(2)->unsigned();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quiz_qroup_id')->references('group_id')->on('quiz_group')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_group_ques', function(Blueprint $table)
        {
            $table->dropForeign('quiz_group_ques_quiz_qroup_id_foreign');        
            $table->dropForeign('quiz_group_ques_status_id_foreign');        
        });
        
        Schema::dropIfExists('quiz_group_ques');
    }
}
