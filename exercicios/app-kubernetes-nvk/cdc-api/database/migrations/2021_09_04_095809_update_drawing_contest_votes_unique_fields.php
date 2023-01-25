<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDrawingContestVotesUniqueFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drawing_contest_votes', function (Blueprint $table) {
            $table->unique(['category_id', 'user_id'], 'user_vote_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // why not?
        Schema::table('drawing_contest_votes', function (Blueprint $table) {
            $table->dropUnique('user_vote_unique');
        });
    }
}
