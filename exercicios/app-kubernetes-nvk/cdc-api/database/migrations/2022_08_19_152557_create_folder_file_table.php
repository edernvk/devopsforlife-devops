<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFolderFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folder_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('file_id');
            $table->foreign('folder_id')
            ->references('id')
            ->on('folders')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('file_id')
            ->references('id')
            ->on('files')
            ->onUpdate('cascade')
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
        Schema::dropIfExists('folder_file');
    }
}
