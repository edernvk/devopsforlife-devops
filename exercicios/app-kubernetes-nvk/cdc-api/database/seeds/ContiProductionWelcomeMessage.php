<?php

use Illuminate\Database\Seeder;

class ContiProductionWelcomeMessage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bot = App\User::where('email', 'bot@penze.com.br')->first();
        $users = App\User::all()->except($bot->id);

        $messages = [
            [
                'from' => $bot->id,
                'title' => "Seja bem-vindo ao CDC Digital",
                'description' => '<p><span style="font-size: 12pt;">Ol&aacute;, </span></p>
                <p><span style="font-size: 12pt;">Eu sou o bot do CDC Digital e vou te auxiliar nessa jornada. </span></p>
                <p><span style="font-size: 12pt;">O objetivo do aplicativo CDC Digital &eacute; transformar a nossa comunica&ccedil;&atilde;o e fazer com que todos os nossos colaboradores fiquem sabendo de tudo sobre n&oacute;s. </span></p>
                <p><span style="font-size: 12pt;">Nesta primeira vers&atilde;o voc&ecirc; pode conferir os comunicados, acessar nosso <strong>Jornal ContiTudo</strong>, ver novidades, acessar o holerite e muito mais.</span></p>
                <p><span style="font-size: 12pt;">Ah, desfrute tanto em computadores quanto em celulares e tablets. Reporte problemas/sugest&otilde;es no menu "<strong><em>Relatar Problema no App</em></strong>", do lado esquerdo. Use o "<em><strong>Fale Conosco</strong></em>" para d&uacute;vidas com o Recursos Humanos. </span></p>
                <p><span style="font-size: 12pt;">Voc&ecirc; &eacute; muito importante nesse projeto. Estamos &agrave; disposi&ccedil;&atilde;o.</span></p>',
            ]
        ];

        foreach ($messages as $message) {
            $msg = App\Message::create([
                'from' => $message['from'],
                'title' => $message['title'],
                'description' => $message['description']
            ]);
            $msg->save();
            $msg->to()->attach($users);
        }
    }
}
