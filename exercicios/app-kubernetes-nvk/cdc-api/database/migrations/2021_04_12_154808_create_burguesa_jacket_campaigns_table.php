<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBurguesaJacketCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_burguesa_jacket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->enum('jacket_1_size', ['PP', 'P', 'M', 'G', 'GG', 'EXG']);
            $table->enum('jacket_2_size', ['PP', 'P', 'M', 'G', 'GG', 'EXG'])->nullable();
            $table->integer('installments_amount');
            $table->timestamp('payment_agreement');
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
        Schema::dropIfExists('campaigns_burguesa_jacket');
    }
}
