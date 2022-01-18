<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_money', function (Blueprint $table) {
            $table->increments('redeem_money_id');
            $table->double('redeem_coin_amt');
            $table->double('redeem_cash_amt');
            $table->integer('updated_by')->unsigned();
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
        Schema::table('redeem_money',function(Blueprint $table){
            $table->dropForeign('redeem_money_updated_by_foreign');           
        });
        Schema::dropIfExists('redeem_money');
    }
}
