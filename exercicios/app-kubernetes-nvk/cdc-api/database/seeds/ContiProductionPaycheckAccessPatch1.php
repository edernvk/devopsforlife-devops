<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContiProductionPaycheckAccessPatch1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $latestId = \App\PaycheckAccess::latest('id')->first('id')->id;

        $jsonPatch = File::get('database/data/novo-acesso-holerite-patch-2020-12-04.json');
        $dataPatch = json_decode($jsonPatch, true);
        $updatedPasswords = [];

        $this->command->info('Quantidade de acessos a atualizar: '. count($dataPatch['data']));

        foreach($dataPatch['data'] as $acesso) {

            $cpfNumbers = $this->onlyNumbers($acesso['cpf']);
            $cpfWithoutPad = ltrim($cpfNumbers, '0');
            $cpfWithPad = null;
            if (strlen($cpfNumbers) < 11) {
                $cpfWithPad = str_pad($cpfNumbers, 11, '0', STR_PAD_LEFT);
            }

            $entry = \App\PaycheckAccess::where('cpf', $cpfNumbers)
                ->orWhere('cpf', $cpfWithoutPad)
                ->when($cpfWithPad, function ($q, $cpfWithPad) {
                    return $q->orWhere('cpf', $cpfWithPad);
                })
                ->first();

            if ($entry !== null) {
                $updated = $entry->update([
                    'password' => $acesso['password'],
                ]);
                if ($updated) {
                    $updatedPasswords[] = $acesso;
                }
            } else {
                $this->command->info('Novo acesso: '. $acesso['email']);

                $user = \App\User::where('cpf', $cpfNumbers)
                    ->orWhere('cpf', $cpfWithoutPad)
                    ->when($cpfWithPad, function ($q, $cpfWithPad) {
                        return $q->orWhere('cpf', $cpfWithPad);
                    })
                    ->first();

                $new = \App\PaycheckAccess::create([
                    'cpf' => $cpfWithPad ?? $cpfNumbers,
                    'email' => $acesso['email'],
                    'password' => $acesso['password'],
                    'user_id' => ($user) ? $user->id : null
                ]);
            }
        }

        $newEntries = \App\PaycheckAccess::where('id', '>', $latestId)->get();
        foreach ($newEntries as $newEntry) {
            $this->command->info($newEntry);
        }
        $this->command->info('Novos registros: '. $newEntries->count());
        $this->command->info('Registros atualizados: '. count($updatedPasswords));
    }

    private function onlyNumbers($value) {
        return preg_replace("/[^A-Za-z0-9]/", '', $value);
    }
}
