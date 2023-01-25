<?php

use Illuminate\Database\Seeder;

class ContiProductionVaccineSurveyCampaignTesters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vaccineSurveyRole = \App\Role::updateOrCreate(
            [
                'name' => 'Tester_PesquisaVacina'
            ],
            [
                'description' => 'Usuários testers do formulário de pesquisa vacinação Covid-19'
            ]
        );

        $testers = \App\User::whereIn('cpf', [
            '37801034864', // b. l. g.
            '38505145879', // q. n. p. b.
            '46483745842', // t. s. p.
            '31447224809', // v. c. f. b.
        ])->get();

        $vaccineSurveyRole->users()->sync($testers);
    }
}
