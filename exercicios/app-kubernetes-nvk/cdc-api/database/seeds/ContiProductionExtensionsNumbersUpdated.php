<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContiProductionExtensionsNumbersUpdated extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        $jsonFormat = '[
//            divisoes => [
//                * => [
//                    nome,
//                    cor,
//                    numeros_sem_area => [
//                        * => [
//                            nome,
//                            ramal
//                        ]
//                    ],
//                    areas => [
//                        * => [
//                            nome,
//                            numeros => [
//                                * => [
//                                    nome,
//                                    ramal
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        ]';

        DB::table('extension_numbers')->delete();
        DB::table('extension_numbers')->truncate();
        DB::table('extension_areas')->delete();
        DB::table('extension_areas')->truncate();
        DB::table('extension_divisions')->delete();
        DB::table('extension_divisions')->truncate();

        $json = File::get('database/data/ramais-v3.json');
        $data = json_decode($json, true);

        foreach($data['divisoes'] as $divisao) {

            // create division
            $divisionEntry = \App\ExtensionDivision::create([
                'name' => $divisao['nome'],
                'color' => $divisao['cor']
            ]);

            // if numeros_sem_areas > 0
            if ($divisao['numeros_sem_area']) {

                foreach($divisao['numeros_sem_area'] as $numeroSemArea) {

                    // create numbers without area
                    $numberEntry = \App\ExtensionNumber::create([
                        'name' => $numeroSemArea['nome'],
                        'number' => $numeroSemArea['ramal']
                    ]);
                    $divisionEntry->numbersWithNoArea()->save($numberEntry);
                }

            }

            // loop trough area numbers
            // create numbers with area
            if ($divisao['areas']) {

                // loop trough areas
                foreach($divisao['areas'] as $area) {

                    // create areas
                    $areaEntry = \App\ExtensionArea::create([
                        'name' => $area['nome'],
                    ]);
                    // relate area with division
                    $divisionEntry->areas()->save($areaEntry);

                    foreach($area['numeros'] as $numeroComArea) {

                        // create numbers without area
                        $numberEntry2 = \App\ExtensionNumber::create([
                            'name' => $numeroComArea['nome'],
                            'number' => $numeroComArea['ramal']
                        ]);
                        $areaEntry->numbers()->save($numberEntry2);
                    }
                }

            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
