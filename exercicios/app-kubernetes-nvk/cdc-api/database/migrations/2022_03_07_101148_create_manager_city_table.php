<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_city', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('city_id');

            $table->foreign('manager_id')
                ->references('id')
                ->on('managers')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');

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
        Schema::dropIfExists('manager_city');
    }
}
