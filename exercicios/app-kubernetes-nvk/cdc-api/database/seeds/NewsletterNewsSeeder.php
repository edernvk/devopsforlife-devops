<?php

use App\NewsletterNews;
use Illuminate\Database\Seeder;

class NewsletterNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(NewsletterNews::class, 5)->create();
    }
}
