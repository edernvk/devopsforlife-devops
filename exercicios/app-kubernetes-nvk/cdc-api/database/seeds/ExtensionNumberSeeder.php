<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ExtensionNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dados = [
            [
                'divisao' => 'Administração',
                'area' => 'Diretoria Executiva',
                'nome' => 'Lucilla',
                'ramal' => '9049',
            ],
            [
                'divisao' => 'Administração',
                'area' => 'Diretoria Executiva',
                'nome' => 'Ariovaldo',
                'ramal' => '9044',
            ],
            [
                'divisao' => 'Administração',
                'area' => 'Diretoria Executiva',
                'nome' => 'Sérgio Coutinho',
                'ramal' => '9045',
            ],
            [
                'divisao' => 'Administração',
                'area' => 'Recepção',
                'nome' => 'Maria Alice / Valéria',
                'ramal' => '9002',
            ],
            [
                'divisao' => 'Administração',
                'area' => 'Comercial',
                'nome' => 'Aprendiz',
                'ramal' => '9249',
            ],
            [
                'divisao' => 'Administração',
                'area' => 'Comercial',
                'nome' => 'Carlos Bitencourt',
                'ramal' => '9061',
            ]
        ];

        /*
            App\ExtensionDivision
                App\ExtensionNumber
                App\ExtensionArea
                    App\ExtensionNumber
        */

        foreach ($dados as $entry) {
            $divisoes[$entry['divisao']] = ['nome' => $entry['divisao']];

                $divisoes[$entry['divisao']]['areas'][][$entry['area']]['nome'] = $entry['area'];

//            }
        }

        Log::info(json_encode($divisoes, true));
    }
}
