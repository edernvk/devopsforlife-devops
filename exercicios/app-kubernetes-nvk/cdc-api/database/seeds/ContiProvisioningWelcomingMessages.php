<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContiProvisioningWelcomingMessages extends Seeder
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
                'title' => "Seja bem-vindo à minha versão 0.1.1 beta.",
                'description' => '
     <p><img title="BANNER-CDC-DIGITAL-FINAL.png" src="http://sandbox.casadiconti.com.br/storage/messages-images/156659500591blobid1566594999876.png" alt="" width="576" height="220" /></p>
     <p><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Ol&aacute;,</span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Parab&eacute;ns por ser um dos primeiros usu&aacute;rios a nos testar. Eu sou o <strong>bot do CDC digital</strong> e vou te auxiliar nessa jornada de testes.</span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">O objetivo do aplicativo <strong>CDC digital</strong> &eacute; transformar a nossa <strong>comunica&ccedil;&atilde;o</strong> e fazer com que a maior parte poss&iacute;vel de nossos colaboradores fiquem sabendo de tudo sobre n&oacute;s. </span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">A primeira vers&atilde;o conta com recursos b&aacute;sicos, como alterar perfis e ver mensagens. </span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Em breve mais novidades.</span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Reporte seus <strong>problemas/dicas</strong> clicando no link </span><a style="color: #6611cc; pointer-events: none; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" href="report" target="_blank" rel="nofollow noopener">http://test.casadiconti.com.br/report</a><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;"> ou no menu do aplicativo. </span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Ah, desfrute tanto em computadores quanto celulares e tablets. </span><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><br style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;" /><span style="color: #202124; font-family: Roboto, Arial, sans-serif; font-size: 16px; font-variant-ligatures: none; letter-spacing: 0.1px; white-space: pre-wrap; background-color: #ffffff;">Voc&ecirc; &eacute; muito importante nesse projeto. Estamos &agrave; disposi&ccedil;&atilde;o. </span></p>
     ',
            ],
            [
                'from' => $bot->id,
                'title' => "Feliz aniversário (exemplo de mensagem)",
                'description' => '<p>Est&aacute; &eacute; uma mensagem de exemplo que pode ser realizada, automatizada, para colaboradores que fazem anivers&aacute;rio, gerando engajamento. :-)&nbsp;</p>',
            ],
            [
                'from' => $bot->id,
                'title' => "Feliz natal. Ho ho ho ho",
                'description' => '<p>Uma mensagem de exemplo de Feliz Natal. Economia, praticidade, rapidez e humaniza&ccedil;&atilde;o.&nbsp;</p>',
            ],
            [
                'from' => $bot->id,
                'title' => "Atualização de sistema (tecnologia)",
                'description' => '
    <p>Oi,</p>
     <p>Eu sou um exemplo de mensagem do depto de tecnologia sobre alguma atualiza&ccedil;&atilde;o de sistema.</p>
     <p>Teremos uma parada das 08h &agrave;s 10h do dia 22/10/2019.&nbsp;</p>
    ',
            ],
            [
                'from' => $bot->id,
                'title' => "Atualização de sistema - exemplo tecnologia",
                'description' => '
    <p>Oi,&nbsp;</p>
     <p>Eu sou um exemplo de mensagem contendo um comunicado do time de tecnologia de uma suposta parada de sistema para atualiza&ccedil;&atilde;o.&nbsp;</p>
     <p>&nbsp;</p>
     <p>Dia 22/10/2019, das 08h &agrave;s 10h.&nbsp;</p>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
    '
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
