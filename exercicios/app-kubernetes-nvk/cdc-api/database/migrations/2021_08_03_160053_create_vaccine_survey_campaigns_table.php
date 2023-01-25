<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineSurveyCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_vaccine_survey', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->enum('local_age_reached', ['yes', 'no']);
            $table->enum('first_dose', ['yes', 'no']);
            $table->enum('second_dose', ['yes', 'no', 'n/a']);
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
        Schema::dropIfExists('campaigns_vaccine_survey');
    }
}
