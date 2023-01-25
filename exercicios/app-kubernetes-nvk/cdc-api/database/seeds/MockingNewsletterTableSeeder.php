<?php

use Illuminate\Database\Seeder;

class MockingNewsletterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Newsletter::class, 10)->create();
    }
}
