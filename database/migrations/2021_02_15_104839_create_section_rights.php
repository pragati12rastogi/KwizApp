<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_rights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->integer('pid');
            $table->integer('show_order');
            $table->boolean('show_menu');
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
        Schema::dropIfExists('section_rights');
    }
}
