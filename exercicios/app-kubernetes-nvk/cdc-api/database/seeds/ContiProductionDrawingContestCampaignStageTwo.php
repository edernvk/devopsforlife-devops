<?php

use Illuminate\Database\Seeder;

class ContiProductionDrawingContestCampaignStageTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stageTwoCampaign = \App\Campaign::where('slug', 'pintando-a-casadiconti-segunda-etapa')->first();
        $stageTwoCampaign->update([
            'entry_date' => '2021-09-16',
            'departure_date' => '2021-09-17'
        ]);

        $drawingContestCommissionRole = \App\Role::updateOrCreate(
            [
                'name' => 'Comission_VotacaoFotos'
            ],
            [
                'description' => 'Usuários selecionados da segunda etapa da Votação Pintando a Casa di Conti'
            ]
        );

        $users = \App\User::whereIn('cpf', [
            '99999999990', // admin ghost
            '27233455805', // g. c. f.
            '21438983808', // s. c.
            '42253885819', // i. c.
        ])->get();

        $drawingContestCommissionRole->users()->sync($users);

    }
}
