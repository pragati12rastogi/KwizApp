<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_question', function (Blueprint $table) {
            $table->increments('question_id');
            $table->integer('contest_id')->unsigned();
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
            $table->foreign('contest_id')->references('id')->on('contest')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_question', function(Blueprint $table)
        {
            $table->dropForeign('contest_question_status_id_foreign');        
            $table->dropForeign('contest_question_contest_id_foreign');        
        });
        Schema::dropIfExists('contest_question');
    }
}
