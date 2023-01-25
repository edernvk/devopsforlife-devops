<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionsOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('question_id');
            $table->string('option');
            $table->tinyInteger('is_right_option')->default(false);
            $table->timestamps();
            $table->foreign('question_id')
                ->references('id')
                ->on('quiz_questions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_questions_options');
    }
}
