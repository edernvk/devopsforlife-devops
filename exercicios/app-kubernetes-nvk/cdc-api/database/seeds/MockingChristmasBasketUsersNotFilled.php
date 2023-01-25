<?php

use App\Campaign;
use Illuminate\Database\Seeder;

class MockingChristmasBasketUsersNotFilled extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaign = Campaign::where('slug', 'cesta-natal')->first();
        $campaign->update([
            'entry_date' => '2022-11-17',
            'departure_date' => '2022-11-19'
        ]);

        $christmasBasketCampaignRole = \App\Role::updateOrCreate(
            [
                'name' => 'Usuarios_CestaNatal2022'
            ],
            [
                'description' => 'Usuários que não preecheram da Cesta de Natal 2021'
            ]
        );

        $users = \App\User::whereIn('cpf', [
            '99999999990'
        ])->get();

        $christmasBasketCampaignRole->users()->sync($users);
    }
}
