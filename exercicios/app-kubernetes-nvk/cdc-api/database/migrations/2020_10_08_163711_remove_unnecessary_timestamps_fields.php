<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecessaryTimestampsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages_users', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages_users', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('states', function (Blueprint $table) {
            $table->timestamps();
        });
    }
}
