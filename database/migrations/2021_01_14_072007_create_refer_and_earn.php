<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferAndEarn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refer_and_earn', function (Blueprint $table) {
            $table->increments('refer_and_earn_id');
            $table->double('join_bonus_amount')->default(0);
            $table->double('refer_bonus_amount')->default(0);
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
        Schema::table('refer_and_earn',function(Blueprint $table){
            $table->dropForeign('refer_and_earn_updated_by_foreign');           
        });
        Schema::dropIfExists('refer_and_earn');
    }
}
