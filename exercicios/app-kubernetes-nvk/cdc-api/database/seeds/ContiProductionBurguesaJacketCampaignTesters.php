<?php

use Illuminate\Database\Seeder;

class ContiProductionBurguesaJacketCampaignTesters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jaquetaBurguesaRole = \App\Role::updateOrCreate(
            [
                'name' => 'Tester_JaquetaBurguesa'
            ],
            [
                'description' => 'Usuários testers do formulário de pedido da Jaqueta Burguesa'
            ]
        );

        $testers = \App\User::whereIn('cpf', [
            '37801034864',
            '31447224809',
            '21888134801',
            '38505145879',
            '38073335840',
        ])->get();

        $jaquetaBurguesaRole->users()->sync($testers);

    }
}
