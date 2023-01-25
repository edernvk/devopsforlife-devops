<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quiz_id');
            $table->string('question')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('quiz_id')
                ->references('id')
                ->on('quiz')
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
        Schema::dropIfExists('quiz_questions');
    }
}
