<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChristmasBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('christmas_baskets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('shipping_address_zipcode', 9);
            $table->string('shipping_address_street_name');
            $table->string('shipping_address_number');
            $table->string('shipping_address_neighbourhood');
            $table->string('shipping_address_city');
            $table->string('shipping_address_complement')->nullable();

            $table->string('name_recipient');
            $table->string('degree_kinship');

            $table->string('suggestion', 500)->nullable();

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
        Schema::dropIfExists('christmas_baskets');
    }
}
