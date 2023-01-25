<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFkProductIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_not_found', function (Blueprint $table) {
            $table->dropForeign('products_not_found_product_id_foreign');
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_not_found', function (Blueprint $table) {
            //
        });
    }
}
