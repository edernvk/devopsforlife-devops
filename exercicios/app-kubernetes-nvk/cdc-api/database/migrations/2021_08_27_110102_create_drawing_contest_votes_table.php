<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawingContestVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawing_contest_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('picture_id');
            $table->unsignedBigInteger('user_id');

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
        Schema::dropIfExists('drawing_contest_votes');
    }
}
