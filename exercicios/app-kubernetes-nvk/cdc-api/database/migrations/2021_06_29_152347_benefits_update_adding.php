<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BenefitsUpdateAdding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('benefits', function (Blueprint $table) {
            $table->nullableMorphs('parentable');
        });

        Schema::table('benefit_areas', function (Blueprint $table) {
            $table->unsignedBigInteger('benefit_division_id')->nullable();
            $table->foreign('benefit_division_id')
                ->references('id')
                ->on('benefit_divisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
