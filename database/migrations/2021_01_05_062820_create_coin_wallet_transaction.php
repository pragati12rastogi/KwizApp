<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinWalletTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_wallet_transaction', function (Blueprint $table) {
            $table->increments('coin_wallet_trans_id');
            $table->integer('coin_wallet_id')->unsigned();
            $table->integer('app_user_id')->unsigned();
            $table->integer('coin_wallet_type')->comment('1=Debit(Redeem),2=Credit(Reward)');
            $table->integer('coin_wallet_trans_status')->comment('1=Pending,2=Approved');
            $table->double('coin_wallet_amount');
            $table->text('coin_wallet_remark');
            $table->timestamp('coin_wallet_trans_at');
            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coin_wallet_id')->references('coin_wallet_id')->on('coin_wallet')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coin_wallet_transaction', function(Blueprint $table)
        {
            $table->dropForeign('coin_wallet_transaction_app_user_id_foreign');        
            $table->dropForeign('coin_wallet_transaction_coin_wallet_id_foreign');        
        });
        Schema::dropIfExists('coin_wallet_transaction');
    }
}
