<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockingExtensionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('extension_numbers')->delete();
        DB::table('extension_numbers')->truncate();
        DB::table('extension_areas')->delete();
        DB::table('extension_areas')->truncate();
        DB::table('extension_divisions')->delete();
        DB::table('extension_divisions')->truncate();

        factory(\App\ExtensionDivision::class, 3)->states(['withOrphanNumbers', 'withNumbers'])->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
