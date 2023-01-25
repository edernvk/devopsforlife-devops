<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtensionsUpdateAdding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extension_numbers', function (Blueprint $table) {
            $table->nullableMorphs('parentable');
        });

        Schema::table('extension_areas', function (Blueprint $table) {
            $table->unsignedBigInteger('extension_division_id')->nullable();
            $table->foreign('extension_division_id')
                ->references('id')
                ->on('extension_divisions');
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
