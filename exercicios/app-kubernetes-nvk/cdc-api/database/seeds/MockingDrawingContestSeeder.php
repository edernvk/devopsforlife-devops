<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockingDrawingContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('drawing_contest_votes')->delete();
        DB::table('drawing_contest_votes')->truncate();
        DB::table('drawing_contest_pictures')->delete();
        DB::table('drawing_contest_pictures')->truncate();
        DB::table('drawing_contest_categories')->delete();
        DB::table('drawing_contest_categories')->truncate();
        $campaign = \App\Campaign::where('slug', 'pintando-casadiconti')->first();
        if ($campaign) $campaign->delete();

        factory(App\Campaign::class)->create([
            'title' => 'Concurso - 13° Pintando a Casa Di Conti',
            'slug' => 'pintando-casadiconti',
            'description' => 'Neste ano você irá ajudar a escolher os desenhos! Vote abaixo, podendo escolher 1 (uma) imagem de cada categoria, que está separada por idade. Sua participação é muito importante para escolha dos desenhos e também dos rostinhos que ilustrarão nosso Calendário 2022!',
            'entry_date' => (new DateTime('2021-09-01'))->format('Y-m-d'),
            'departure_date' => (new DateTime('2021-09-30'))->format('Y-m-d')
        ]);

        factory(App\DrawingContestCategory::class, 8)->create()->each(function ($category) {
            factory(App\DrawingContestPicture::class, 6)->create(['category_id' => $category->id]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
