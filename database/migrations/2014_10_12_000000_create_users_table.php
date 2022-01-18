<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->default(1)->increments;
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('otp_code')->length(6)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('status_id')->default(2)->unsigned();
            $table->string('profile_picture');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_status_id_foreign');
        
        });
        
        Schema::dropIfExists('users');
    }
}
