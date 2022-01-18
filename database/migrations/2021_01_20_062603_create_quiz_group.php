<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_group', function (Blueprint $table) {
            $table->increments('group_id');
            $table->integer('quiz_category_id')->unsigned();
            $table->string('quiz_title');
            $table->integer('status_id')->default(2)->unsigned();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quiz_category_id')->references('quiz_category_id')->on('quiz_category')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_group', function(Blueprint $table)
        {
            $table->dropForeign('quiz_group_quiz_category_id_foreign');        
            $table->dropForeign('quiz_group_status_id_foreign');        
        });
        
        Schema::dropIfExists('quiz_group');
    }
}
