<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaycheckAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paycheck_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 300);
            $table->string('password', 300);
            $table->string('cpf')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paycheck_access');
    }
}
