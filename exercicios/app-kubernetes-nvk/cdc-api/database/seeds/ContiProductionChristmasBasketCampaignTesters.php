<?php

use Illuminate\Database\Seeder;

class ContiProductionChristmasBasketCampaignTesters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $christmasBasketCampaignRole = \App\Role::updateOrCreate(
            [
                'name' => 'Tester_CestaNatal2021'
            ],
            [
                'description' => 'Usuários testers da Cesta de Natal 2021'
            ]
        );

        $testers = \App\User::whereIn('cpf', [
            '46744023870', // a. a. r. l.
            '50078243807', // b. e. de a.
            '21888134801', // c. r. o. m.
            '31672510856', // t. a. s. c.
            '38073335840', // m. a. f. s.
            '38505145879', // q. n. p. b.
            '21438983808', // s. c.
            '46483745842', // t. s. p.
            '37801034864', // b. l. g.
            '31447224809', // v. de c. f. b.
        ])->get();

        $christmasBasketCampaignRole->users()->sync($testers);
    }
}
