<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawingContestPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawing_contest_pictures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('drawing_contest_categories')
                ->onDelete('cascade');

            $table->string('url');
            $table->string('subscription');
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
        Schema::dropIfExists('drawing_contest_pictures');
    }
}
