<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContrastColumnToNewsletterNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_news', function (Blueprint $table) {
            $table->boolean('contrast')->default(false)->after('content');
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
            $table->dropColumn('contrast');
        });
    }
}
