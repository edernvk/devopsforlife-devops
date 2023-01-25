<?php

use Illuminate\Database\Seeder;

class AppReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create simple colaborator user (generic and not-admin)
        // create generic token for user
        // send user at least 1 message from "Comunicação Interna"

        $colaborador = factory(\App\User::class)
            ->states(['colaborador', 'approved', 'aniversario-hoje'])
            ->create([
                'name' => 'Colaborador',
                'cpf' => '99999999999',
                'email' => 'colaborador@example.com',
                'mobile' => '10987654321'
            ]);
        \App\ChristmasToken::create([
            'cpf' => $colaborador->cpf,
            'token' => 'l0r3m 1p5um',
            'user_id' => $colaborador->id
        ]);

        factory(\App\User::class)
            ->states(['admin', 'approved', 'aniversario-amanha'])
            ->create([
                'name' => 'Comunicação Interna',
                'cpf' => '00000000000',
                'email' => 'comunicacao@example.com',
                'mobile' => '12345678910',
                'avatar' => 'http://cdc-api.test/storage/users-avatars/bot.png'
            ]);

        factory(\App\Message::class)
            ->states(['from-comunicacao-interna', 'to-everybody'])
            ->create([
                'title' => 'Exemplo de comunicado aos colaboradoradores',
                'description' => 'Este é um exemplo de comunicado que o colaborador receberá.',
                'publish_datetime' => now()->startOfYear()->setHour(12)->toDateTimeString()
            ]);
    }
}
