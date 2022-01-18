<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_bonus', function (Blueprint $table) {
            $table->increments('bonus_id');
            $table->double('monday')->default(0);
            $table->double('tuesday')->default(0);
            $table->double('wednesday')->default(0);
            $table->double('thursday')->default(0);
            $table->double('friday')->default(0);
            $table->double('saturday')->default(0);
            $table->double('sunday')->default(0);
            $table->bigInteger('created_by')->unsigned();
            $table->timestamp('last_updated_at');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
     
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
            $table->dropForeign('daily_bonus_created_by_foreign');           
        });
        Schema::dropIfExists('daily_bonus');
    }
}
