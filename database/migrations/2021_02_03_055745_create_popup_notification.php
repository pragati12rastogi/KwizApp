<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopupNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popup_notification', function (Blueprint $table) {
            $table->id();
            $table->boolean('display')->default(0);
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
        Schema::table('popup_notification',function(Blueprint $table){
            $table->dropForeign('popup_notification_updated_by_foreign');           
        });
        Schema::dropIfExists('popup_notification');
    }
}
