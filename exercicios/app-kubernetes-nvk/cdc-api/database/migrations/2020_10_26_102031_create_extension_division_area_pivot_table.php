<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionDivisionAreaPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_division_area', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('division_id');
            $table->foreign('division_id')
                ->references('id')
                ->on('extension_divisions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')
                ->references('id')
                ->on('extension_areas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extension_division_area');
    }
}
