<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtensionsUpdateRemoving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('extension_division_area');

        Schema::table('extension_numbers', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['division_id', 'area_id']);
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
