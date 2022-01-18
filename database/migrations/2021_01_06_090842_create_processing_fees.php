<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_fees', function (Blueprint $table) {
            $table->increments('processing_fees_id');
            $table->double('processing_fees_value');
            $table->integer('processing_fees_type')->comment('1=>Cash Wallet,2=>Coin Wallet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processing_fees');
    }
}
