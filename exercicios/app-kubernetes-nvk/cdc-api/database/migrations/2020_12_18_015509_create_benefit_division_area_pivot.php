<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenefitDivisionAreaPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_division_area', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('benefit_division_id');
            $table->foreign('benefit_division_id')
                ->references('id')
                ->on('benefit_divisions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('benefit_area_id');
            $table->foreign('benefit_area_id')
                ->references('id')
                ->on('benefit_areas')
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
        Schema::dropIfExists('benefit_division_area_pivot');
    }
}
