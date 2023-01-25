<?php

use App\Campaign;
use App\VaccineCampaing;
use Illuminate\Database\Seeder;

class MockingVaccineCampaingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campaign::create([
            'title' => 'Campanha de Vacinação',
            'slug' => 'campanha-vacinacao',
            'description' => 'Atenção, será realizada em 2022 a Campanha de Vacinação para colaboradores, contra a gripe Influenza. Interessados devem confirmar abaixo a solicitação por vacina, confirmar o pagamento e salvar sua resposta.',
            'entry_date' => '2022-01-07',
            'departure_date' => '2022-01-14'
        ]);
    }
}
