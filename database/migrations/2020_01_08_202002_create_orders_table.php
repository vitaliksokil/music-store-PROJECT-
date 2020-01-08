<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('quantity')->default(1);
            $table->unsignedInteger('total_price');
            $table->string('region');
            $table->string('city');
            $table->char('delivery',5);
            $table->string('post_office');
            $table->string('client_name');
            $table->string('client_surname');
            $table->string('client_middlename');
            $table->string('client_email');
            $table->string('client_phone_number');
            $table->boolean('is_verified')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->unique(['user_id','product_id']);

        });
        Schema::table('orders',function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('orders');
    }
}
