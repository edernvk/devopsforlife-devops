<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContiProductionBenefitsPartnersUpdated extends Seeder
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
//            divisions => [
//                * => [
//                    name,
//                    areas => [
//                        * => [
//                            name,
//                            benefits => [
//                                * => [
//                                    partner,
//                                    contact,
//                                    benefit
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        ]';

        DB::table('benefits')->delete();
        DB::table('benefits')->truncate();
        DB::table('benefit_areas')->delete();
        DB::table('benefit_areas')->truncate();
        DB::table('benefit_divisions')->delete();
        DB::table('benefit_divisions')->truncate();

        $json = File::get('database/data/beneficios-v3.json');
        $data = json_decode($json, true);

        foreach($data['divisions'] as $divisao) {

            // create division
            $divisionEntry = \App\BenefitDivision::create([
                'name' => $divisao['name'],
                'ionicon' => $divisao['ionicon'] ?: 'bookmarks'
            ]);

            // for now, there is no benefits without area

            // loop trough area benefits
            // create benefits with area
            if ($divisao['areas']) {

                // loop trough areas
                foreach($divisao['areas'] as $area) {

                    // create areas
                    $areaEntry = \App\BenefitArea::create([
                        'name' => $area['name'],
                    ]);
                    // relate area with division
                    $divisionEntry->areas()->save($areaEntry);

                    foreach($area['benefits'] as $beneficio) {

                        // create numbers without area
                        $benefitEntry = \App\Benefit::create([
                            'partner' => $beneficio['partner'],
                            'contact' => $beneficio['contact'],
                            'benefit' => $beneficio['benefit']
                        ]);
                        $areaEntry->benefits()->save($benefitEntry);
                    }
                }

            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
