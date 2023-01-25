<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenefits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('partner');
            $table->string('contact');
            $table->string('benefit');
            $table->unsignedBigInteger('benefit_division_id');
            $table->foreign('benefit_division_id')
                ->references('id')
                ->on('benefit_divisions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('benefit_area_id')->nullable();
            $table->foreign('benefit_area_id')
                ->references('id')
                ->on('benefit_areas')
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
        Schema::dropIfExists('benefits');
    }
}
