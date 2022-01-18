<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_wallet', function (Blueprint $table) {
            $table->increments('cash_wallet_id');
            $table->integer('app_user_id')->unsigned();
            $table->double('cash_wallet_balance');
            $table->timestamps();
            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('cash_wallet', function(Blueprint $table)
        {
            $table->dropForeign('cash_wallet_app_user_id_foreign');        
        });

        Schema::dropIfExists('cash_wallet');
    }
}
