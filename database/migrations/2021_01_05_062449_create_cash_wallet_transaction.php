<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashWalletTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_wallet_transaction', function (Blueprint $table) {
            $table->increments('cash_wallet_trans_id');
            $table->integer('cash_wallet_id')->unsigned();
            $table->integer('app_user_id')->unsigned();
            $table->integer('cash_wallet_type')->comment('1=Debit,2=Credit,3=Transfer from coin wallet');
            $table->integer('cash_wallet_trans_status')->comment('1=Pending,2=Approved');
            $table->double('cash_wallet_amount');
            $table->text('cash_wallet_remark');
            $table->timestamp('cash_wallet_trans_at');
            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cash_wallet_id')->references('cash_wallet_id')->on('cash_wallet')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_wallet_transaction', function(Blueprint $table)
        {
            $table->dropForeign('cash_wallet_transaction_app_user_id_foreign');        
            $table->dropForeign('cash_wallet_transaction_cash_wallet_id_foreign');        
        });
        Schema::dropIfExists('cash_wallet_transaction');
    }
}
