<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDescriptionVideocastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // require doctrine/dbal
         Schema::table('videocasts', function (Blueprint $table) {
            $table->string('description', 500)->change();
         });

//        DB::statement('ALTER TABLE videocasts MODIFY description VARCHAR(500)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // require doctrine/dbal
         Schema::table('videocasts', function (Blueprint $table) {
            $table->string('description', 300)->change();
         });

//        DB::statement('ALTER TABLE videocasts MODIFY description VARCHAR(300)');
    }
}
