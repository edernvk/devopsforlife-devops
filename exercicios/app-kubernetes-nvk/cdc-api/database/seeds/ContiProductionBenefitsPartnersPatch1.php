<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContiProductionBenefitsPartnersPatch1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $latestId = \App\Benefit::latest('id')->first('id');

        $jsonPatch = File::get('database/data/beneficios-patch-2021-08-26.json');
        $dataPatch = json_decode($jsonPatch, true);
        $updatedBenefits = [];

        $this->command->info('Quantidade de benefícios para atualizar: '. count($dataPatch['data']));

        foreach($dataPatch['data'] as $beneficio) {
            $partner = \App\Benefit::where('partner', 'like', '%'.$beneficio['partner'].'%')->first();

            ($partner) ? $this->command->info($partner->id.' - '.$partner->partner) : $this->command->info($beneficio['partner']);

            if ($partner) {
                $updated = $partner->update([
                    'contact' => $beneficio['contact'],
                    'benefit' => $beneficio['benefit'],
                ]);
                if ($updated) {
                    $updatedBenefits[] = $beneficio;
                }
            }
        }

        $this->command->info('Registros atualizados: '. count($updatedBenefits));
    }
}
