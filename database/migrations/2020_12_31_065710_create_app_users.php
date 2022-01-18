<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('app_user_id');
            $table->integer('status_id')->default(1)->unsigned();
            $table->integer('register_type')->default(1)->comment('1=Traditional;2=Facebook;3=Google');
            $table->string('is_verified')->default(0);
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->integer('otp_code')->length(6);
            $table->string('profile_pic');
            $table->date('dob');
            $table->string('refer_code');
            $table->tinyInteger('refer_code_used');
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('app_users', function(Blueprint $table)
        {
            $table->dropForeign('app_users_status_id_foreign');
        });
        
        Schema::dropIfExists('app_users');
    }
}
