<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockingBenefitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('benefits')->delete();
        DB::table('benefits')->truncate();
        DB::table('benefit_areas')->delete();
        DB::table('benefit_areas')->truncate();
        DB::table('benefit_divisions')->delete();
        DB::table('benefit_divisions')->truncate();

        factory(\App\BenefitDivision::class, 3)->states(['withOrphanBenefits', 'withBenefits'])->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
