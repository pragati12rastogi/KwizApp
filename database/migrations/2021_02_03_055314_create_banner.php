<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('banner_img');
            $table->boolean('display')->default(0);
            $table->integer('updated_by');
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
        Schema::table('banner',function(Blueprint $table){
            $table->dropForeign('banner_updated_by_foreign');           
        });
        Schema::dropIfExists('banner');
    }
}
