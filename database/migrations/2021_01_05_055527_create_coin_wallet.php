<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_wallet', function (Blueprint $table) {
            $table->increments('coin_wallet_id');
            $table->integer('app_user_id')->unsigned();
            $table->double('coin_wallet_balance');
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
        Schema::table('coin_wallet', function(Blueprint $table)
        {
            $table->dropForeign('coin_wallet_app_user_id_foreign');        
        });
        Schema::dropIfExists('coin_wallet');
    }
}
