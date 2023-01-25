<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockingDrawingContestFourteenthCampaing extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('drawing_contest_votes')->delete();
        DB::table('drawing_contest_votes')->truncate();

        $campaign1 = \App\Campaign::where('slug', 'pintando-a-casadiconti')->first();
        if ($campaign1) $campaign1->delete();
        $campaign2 = \App\Campaign::where('slug', 'pintando-a-casadiconti-segunda-etapa')->first();
        if ($campaign2) $campaign2->delete();

        $campaignStageOne = App\Campaign::create([
            'title' => 'Concurso - 14° Pintando a Casa Di Conti',
            'slug' => 'pintando-a-casadiconti',
            'description' => 'Neste ano você irá ajudar a escolher os desenhos! <br/>
                    Vote abaixo, podendo escolher 1 (uma) imagem de cada categoria que está separada por idade. <br/>
                    Sua participação é muito importante para a escolha dos desenhos e também dos rostinhos que ilustrarão nosso Calendário 2023.',
            'entry_date' => '2022-09-20',
            'departure_date' => '2022-09-22'
        ]);

        $campaignStageTwo = App\Campaign::create([
            'title' => 'Concurso - 14° Pintando a Casa Di Conti - Segunda Etapa',
            'slug' => 'pintando-a-casadiconti-segunda-etapa',
            'description' => 'Neste ano você irá ajudar a escolher os desenhos! <br/>
                Vote abaixo, podendo escolher 1 (uma) imagem de cada categoria que está separada por idade. <br/>
                Sua participação é muito importante para a escolha dos desenhos e também dos rostinhos que ilustrarão nosso Calendário 2023.',
            'entry_date' => '2022-09-22',
            'departure_date' => '2022-09-23'
        ]);
    }
}
