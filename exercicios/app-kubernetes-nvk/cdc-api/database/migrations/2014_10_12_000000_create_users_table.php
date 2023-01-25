<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('email')->nullable();
            $table->string('registration')->nullable();
            $table->string('mobile');
            $table->string('avatar')->nullable()->default(Storage::url('users-avatars/default-avatar.jpg'));
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('approved')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
