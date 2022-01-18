<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleSectionRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_section_rights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('section_id')->unsigned();

            $table->foreign('role_id')->references('role_id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('section_rights')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_section_rights',function(Blueprint $table){
            $table->dropForeign('role_section_rights_role_id_foreign');           
            $table->dropForeign('role_section_rights_section_id_foreign');           
        });
        Schema::dropIfExists('role_section_rights');
    }
}
