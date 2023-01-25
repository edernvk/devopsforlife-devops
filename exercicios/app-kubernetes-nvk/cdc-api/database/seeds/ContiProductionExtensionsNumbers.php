<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ContiProductionExtensionsNumbers extends Seeder
{
    /**
     * 2021-06-29
     *
     * !!! DO NOT USE THIS SEEDER !!!
     *
     * SEE ContiProductionExtensionsNumbersUpdated FOR NEW USAGE.
     *
     * THE INFORMATION HIERARCHY USED BY THIS SEEDER IS DEPRECATED IN FAVOR
     *  OF THE NEW, IMPROVED ONE, USING POLYMORPHIC RELATIONSHIPS.
     */

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
        DB::table('extension_division_area')->delete();
        DB::table('extension_division_area')->truncate();
        DB::table('extension_divisions')->delete();
        DB::table('extension_divisions')->truncate();
        DB::table('extension_areas')->delete();
        DB::table('extension_areas')->truncate();

        $json = File::get('database/data/ramais-v2.json');
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
                        'number' => $numeroSemArea['ramal'],
                        'division_id' => $divisionEntry->id
                    ]);
//                    $numberEntry->division()->save($divisionEntry);
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
//                    $areaEntry->divisions()->attach($divisionEntry);
                    $divisionEntry->areas()->attach($areaEntry);

                    foreach($area['numeros'] as $numeroComArea) {

                        // create numbers without area
                        $numberEntry2 = \App\ExtensionNumber::create([
                            'name' => $numeroComArea['nome'],
                            'number' => $numeroComArea['ramal'],
                            'division_id' => $divisionEntry->id,
                            'area_id' => $areaEntry->id
                        ]);
//                        $numberEntry2->division()->save($divisionEntry);
//                        $numberEntry2->area()->save($areaEntry);
                    }
                }

            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
