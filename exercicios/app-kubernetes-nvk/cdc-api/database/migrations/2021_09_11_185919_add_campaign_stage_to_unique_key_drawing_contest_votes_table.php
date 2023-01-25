<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignStageToUniqueKeyDrawingContestVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // avoids 'cant drop unique used in foreign'
        Schema::table('drawing_contest_votes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['picture_id']);
            $table->dropForeign(['user_id']);
            $table->dropUnique('user_vote_unique');
        });

        // adds foreign back
        Schema::table('drawing_contest_votes', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('drawing_contest_categories')
                ->onDelete('cascade');

            $table->foreign('picture_id')
                ->references('id')
                ->on('drawing_contest_pictures')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // updates unique index
        Schema::table('drawing_contest_votes', function (Blueprint $table) {
            $table->unique(['category_id', 'campaign_stage', 'user_id'], 'user_vote_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unique_key_drawing_contest_votes', function (Blueprint $table) {
            //
        });
    }
}
