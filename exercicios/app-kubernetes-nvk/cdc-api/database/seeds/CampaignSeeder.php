<?php

use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Campaign::class)->create([
            'title' => 'Pintando CasaDiConti',
            'slug' => 'pintando-casadiconti',
            'description' => 'Concurso Pintando CasaDiConti',
            'entry_date' => (new DateTime('2021-09-01'))->format('Y-m-d'),
            'departure_date' => (new DateTime('2021-09-10'))->format('Y-m-d')
        ]);
    }
}
