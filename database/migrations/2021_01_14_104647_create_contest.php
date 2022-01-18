<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest', function (Blueprint $table) {
            $table->increments('contest_id');
            $table->string('contest_name');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('user_can_join')->comment('Total User can join');
            $table->string('contest_icon');
            $table->integer('status_id')->default(2)->unsigned();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest', function(Blueprint $table)
        {
            $table->dropForeign('contest_status_id_foreign');        
        });

        Schema::dropIfExists('contest');
    }
}
