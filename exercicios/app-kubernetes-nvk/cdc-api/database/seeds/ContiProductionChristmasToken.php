<?php

use App\Exports\ReportExport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContiProductionChristmasToken extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('christmas_tokens')->delete();
        DB::table('christmas_tokens')->truncate();

        $json = File::get('database/data/token-qrcode.json');
        $data = json_decode($json, true);

        foreach($data['data'] as $token) {
            $cpfNumbers = $this->onlyNumbers($token['cpf']);
            $cpfWithoutPad = ltrim($cpfNumbers, '0');
            $cpfWithPad = false;
            if (strlen($cpfNumbers) < 11) {
                $cpfWithPad = str_pad($cpfNumbers, 11, '0', STR_PAD_LEFT);
            }

            $user = \App\User::where('cpf', $cpfNumbers)
                ->orWhere('cpf', $cpfWithoutPad)
                ->when($cpfWithPad, function ($q, $cpfWithPad) {
                    return $q->orWhere('cpf', $cpfWithPad);
                })
                ->first();

            $new = \App\ChristmasToken::create([
                'cpf' => $cpfNumbers,
                'token' => $token['token'],
                'user_id' => ($user) ? $user->id : null
            ]);
        }

        // USUÁRIOS SEM ACESSO
        $userCDCNaoLista = \App\User::doesntHave('christmas_token')->withoutGhosts()->approved()->orderBy('name')->get(['id','cpf','name']);
        $this->command->info("Usuários cadastrados no CDC Digital que não estão na lista: " . $userCDCNaoLista->count());

        $userCDCNaoLista_basename = 'reports/christmas-tokens/users-in-cdc-not-on-list';
        $userCDCNaoLista_headers = ['cpf', 'name'];
        $userCDCNaoLista_entries = [];
        foreach ($userCDCNaoLista as $user) {
            $row = [];
            $row[] = $user->cpf;
            $row[] = $user->name;
            $userCDCNaoLista_entries[] = $row;
        }
        ReportExport::generateCsv($userCDCNaoLista_basename, $userCDCNaoLista_headers, $userCDCNaoLista_entries);


        // USUÁRIOS NA LISTA MAS NÃO NO SISTEMA
        $userNaListaNaoCDC = \App\ChristmasToken::whereNull('user_id')->get();
        $this->command->info("Usuários na lista que não estão no CDC Digital: " . $userNaListaNaoCDC->count());
        $userNaListaNaoCDC_basename = 'reports/christmas-tokens/users-on-list-not-in-cdc';
        $userNaListaNaoCDC_headers = ['cpf', 'email'];
        $userNaListaNaoCDC_entries = [];
        foreach ($userNaListaNaoCDC as $acesso) {
            $row = [];
            $row[] = $acesso->cpf;
            $row[] = $acesso->email;
            $userNaListaNaoCDC_entries[] = $row;
        }
        ReportExport::generateCsv($userNaListaNaoCDC_basename, $userNaListaNaoCDC_headers, $userNaListaNaoCDC_entries);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function onlyNumbers($value) {
        return preg_replace("/[^A-Za-z0-9]/", '', $value);
    }
}
