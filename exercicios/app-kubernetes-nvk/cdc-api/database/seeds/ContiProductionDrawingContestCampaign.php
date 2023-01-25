<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContiProductionDrawingContestCampaign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // at this point it should have
        // already run `php artisan upload:pictures`
        // (drawing-contest categories+pictures upload)

        // this will only prepare the database for a clean vote start

        DB::table('drawing_contest_votes')->delete();
        DB::table('drawing_contest_votes')->truncate();

        $campaign1 = \App\Campaign::where('slug', 'pintando-a-casadiconti')->first();
        if ($campaign1) $campaign1->delete();
        $campaign2 = \App\Campaign::where('slug', 'pintando-a-casadiconti-segunda-etapa')->first();
        if ($campaign2) $campaign2->delete();

        $campaignStageOne = App\Campaign::create([
            'title' => 'Concurso - 13° Pintando a Casa Di Conti',
            'slug' => 'pintando-a-casadiconti',
            'description' => 'Neste ano você irá ajudar a escolher os desenhos! Vote abaixo, podendo escolher 1 (uma) imagem de cada categoria, que está separada por idade. Sua participação é muito importante para escolha dos desenhos e também dos rostinhos que ilustrarão nosso Calendário 2022!',
            'entry_date' => '2021-09-13',
            'departure_date' => '2021-09-14'
        ]);

        $campaignStageTwo = App\Campaign::create([
            'title' => 'Concurso - 13° Pintando a Casa Di Conti - Segunda Etapa',
            'slug' => 'pintando-a-casadiconti-segunda-etapa',
            'description' => 'Neste ano você irá ajudar a escolher os desenhos! Vote abaixo, podendo escolher 1 (uma) imagem de cada categoria, que está separada por idade. Sua participação é muito importante para escolha dos desenhos e também dos rostinhos que ilustrarão nosso Calendário 2022!',
            'entry_date' => '2021-09-15',
            'departure_date' => '2021-09-15'
        ]);
    }
}
