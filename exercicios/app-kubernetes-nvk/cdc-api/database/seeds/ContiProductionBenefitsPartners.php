<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContiProductionBenefitsPartners extends Seeder
{
    /**
     * 2021-06-29
     *
     * !!! DO NOT USE THIS SEEDER !!!
     *
     * SEE ContiProductionBenefitsPartnersUpdated FOR NEW USAGE.
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
        DB::table('benefit_division_area')->delete();
        DB::table('benefit_division_area')->truncate();

        $json = File::get('database/data/beneficios.json');
        $data = json_decode($json, true);

        foreach($data['divisions'] as $divisao) {

            // create division
            $divisionEntry = \App\BenefitDivision::create([
                'name' => $divisao['name'],
                'ionicon' => $divisao['ionicon'] ?: 'bookmarks'
            ]);

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
                    $divisionEntry->areas()->attach($areaEntry);

                    foreach($area['benefits'] as $beneficio) {

                        // create numbers without area
                        $benefitEntry = \App\Benefit::create([
                            'partner' => $beneficio['partner'],
                            'contact' => $beneficio['contact'],
                            'benefit' => $beneficio['benefit'],
                            'benefit_division_id' => $divisionEntry->id,
                            'benefit_area_id' => $areaEntry->id
                        ]);

                    }
                }

            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
