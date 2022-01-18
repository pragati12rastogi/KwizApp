<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_references', function (Blueprint $table) {
            $table->increments('reference_id');
            $table->integer('app_user_id')->unsigned();
            $table->integer('joinee_id')->unsigned()->comment('app user id who joined using reference');
            $table->integer('bonus_amount');
            $table->timestamp('joined_at');
            $table->foreign('app_user_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('joinee_id')->references('app_user_id')->on('app_users')->onUpdate('cascade')->onDelete('cascade');
      

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_references',function(Blueprint $table){
            $table->dropForeign('user_references_app_user_id_foreign');           
            $table->dropForeign('user_references_joinee_id_foreign');           
        });
        Schema::dropIfExists('user_references');
    }
}
