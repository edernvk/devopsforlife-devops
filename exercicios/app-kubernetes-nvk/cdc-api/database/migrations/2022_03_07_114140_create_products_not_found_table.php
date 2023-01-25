<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsNotFoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_not_found', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('establishment_name');
            $table->string('establishment_address');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('products_not_found');
    }
}
