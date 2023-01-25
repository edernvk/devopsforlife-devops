<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContiProductionChristmasBasketCampaign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('christmas_baskets')->delete();
        DB::table('christmas_baskets')->truncate();

        $campaignTest = \App\Campaign::where('slug', 'cesta-natal')->first();
        if ($campaignTest) $campaignTest->delete();

        $campaign = \App\Campaign::create([
            'title' => 'Entrega da Cesta de Natal 2022',
            'slug' => 'cesta-natal',
            'description' => 'Abaixo insira as informações de endereço onde deve ser entregue a sua Cesta de Natal neste ano. Preencha todos os campos com atenção para que não haja inconformidades na entrega.',
            'entry_date' => '2022-11-01',
            'departure_date' => '2022-11-11'
        ]);
    }
}
