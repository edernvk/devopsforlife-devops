<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveColumnToNewsletterNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_news', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->timestamp('desactivated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_news', function (Blueprint $table) {
            //
        });
    }
}
