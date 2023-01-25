<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('number');
            $table->unsignedBigInteger('division_id');
            $table->foreign('division_id')
                ->references('id')
                ->on('extension_divisions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')
                ->references('id')
                ->on('extension_areas')
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
        Schema::dropIfExists('extension_numbers');
    }
}
