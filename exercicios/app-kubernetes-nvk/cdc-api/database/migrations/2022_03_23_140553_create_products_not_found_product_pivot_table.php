<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsNotFoundProductPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_not_found_product_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('products_not_found_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('products_not_found_id')->references('id')->on('products_not_found')->onDelete('cascade');

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
        Schema::dropIfExists('products_not_found_product_pivot');
    }
}
