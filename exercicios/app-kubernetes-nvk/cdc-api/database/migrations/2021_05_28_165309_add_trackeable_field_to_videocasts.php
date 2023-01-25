<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackeableFieldToVideocasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videocasts', function (Blueprint $table) {
            $table->boolean('trackeable')
                ->default(false)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videocasts', function (Blueprint $table) {
            $table->dropColumn('trackeable');
        });
    }
}
