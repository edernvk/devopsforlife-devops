<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdressFieldsToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('address_street_name')->nullable()->after('city_id');
            $table->string('address_number')->nullable()->after('address_street_name');
            $table->string('address_neighbourhood')->nullable()->after('address_number');
            $table->string('address_postal_code', 8)->nullable()->after('address_neighbourhood');
            $table->string('shipping_address_street_name')->nullable()->after('address_postal_code');
            $table->string('shipping_address_number')->nullable()->after('shipping_address_street_name');
            $table->string('shipping_address_neighbourhood')->nullable()->after('shipping_address_number');
            $table->string('shipping_address_postal_code', 8)->nullable()->after('shipping_address_neighbourhood');
            $table->unsignedBigInteger('shipping_address_city_id')->nullable()->after('shipping_address_postal_code'); // FK
            $table->string('shipping_address_recipient')->nullable()->after('shipping_address_city_id');
            $table->string('shipping_address_recipient_kinship')->nullable()->after('shipping_address_recipient');

            $table->foreign('shipping_address_city_id')->references('id')->on('cities');
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
            $table->dropForeign(['shipping_address_city_id']);
            $table->dropColumn([
                'address_street_name',
                'address_number',
                'address_neighbourhood',
                'address_postal_code',
                'shipping_address_street_name',
                'shipping_address_number',
                'shipping_address_neighbourhood',
                'shipping_address_postal_code',
                'shipping_address_city_id',
                'shipping_address_recipient',
                'shipping_address_recipient_kinship'
            ]);
        });
    }
}
