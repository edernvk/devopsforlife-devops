<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishDatetimeAndStatusIdFieldsInNewsletterNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_news', function (Blueprint $table) {
            $table->bigInteger('status_id')->default(3)->after('content');
            $table->timestamp('publish_datetime')->nullable()->after('status_id');
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
            $table->dropColumn('publish_datetime');
            $table->dropColumn('status_id');
        });
    }
}
