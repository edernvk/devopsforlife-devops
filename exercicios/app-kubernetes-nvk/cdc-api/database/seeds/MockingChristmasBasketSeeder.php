<?php

use App\Campaign;
use App\ChristmasBasket;
use Illuminate\Database\Seeder;

class MockingChristmasBasketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaign = \App\Campaign::where('slug', 'cesta-natal')->first();
        if ($campaign) $campaign->delete();
        factory(Campaign::class)->create([
            'title' => 'Cesta de Natal 2022',
            'description' => 'Leia todos os campos com atenção para receber sua Cesta!',
            'slug' => 'cesta-natal',
            'entry_date' => (new DateTime('2022-11-01'))->format('Y-m-d'),
            'departure_date' => (new DateTime('2022-11-30'))->format('Y-m-d')
        ]);

        factory(ChristmasBasket::class, 5)->create();
    }
}
