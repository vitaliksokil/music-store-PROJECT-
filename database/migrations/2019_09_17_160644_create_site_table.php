<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->integer('id')->default(1)->unique();
            $table->string('sitename');
            $table->string('logo');
            $table->string('town');
            $table->string('street');
            $table->string('phonenumber');
            $table->string('schedule');
            $table->string('vk');
            $table->string('facebook');
            $table->string('olx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site');
    }
}
