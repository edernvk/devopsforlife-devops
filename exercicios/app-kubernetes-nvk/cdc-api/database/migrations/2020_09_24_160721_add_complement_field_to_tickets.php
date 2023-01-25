<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComplementFieldToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('shipping_address_complement')->after('shipping_address_postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('shipping_address_complement');
        });
    }
}
