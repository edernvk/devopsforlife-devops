<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoverMagazinesSetDefaultNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magazines', function (Blueprint $table) {
            $table->string('cover')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('magazines', function (Blueprint $table) {
            $table->string('cover')->nullable()->default(Storage::url('magazines-covers/default-cover.png'))->change();
        });
    }
}
