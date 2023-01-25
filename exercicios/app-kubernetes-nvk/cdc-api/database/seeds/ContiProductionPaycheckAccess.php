<?php

use App\Exports\ReportExport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContiProductionPaycheckAccess extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('paycheck_access')->delete();
        DB::table('paycheck_access')->truncate();

        $json = File::get('database/data/novo-acesso-holerite.json');
        $data = json_decode($json, true);

        foreach($data['data'] as $acesso) {
            $cpfNumbers = $this->onlyNumbers($acesso['cpf']);
            $cpfWithoutPad = ltrim($cpfNumbers, '0');
            $cpfWithPad = false;
            if (strlen($cpfNumbers) < 11) {
                $cpfWithPad = str_pad($cpfNumbers, 11, '0', STR_PAD_LEFT);
            }

            // USE LTRIM BECAUSE THEY PAD 0 AT BEGGINING TO EQUALS 11 CHARS
//            $user = \App\User::where('cpf', ltrim($cpfClean, '0'))->first();
//            $user = \App\User::where('cpf', $cpfNumbers)->orWhere('cpf', $cpfWithoutPad)->first();

            $user = \App\User::where('cpf', $cpfNumbers)
                ->orWhere('cpf', $cpfWithoutPad)
                ->when($cpfWithPad, function ($q, $cpfWithPad) {
                    return $q->orWhere('cpf', $cpfWithPad);
                })
                ->first();

            $new = \App\PaycheckAccess::create([
                'cpf' => $cpfNumbers,
                'email' => $acesso['email'],
                'password' => $acesso['password'],
                'user_id' => ($user) ? $user->id : null
            ]);
        }

        // USUÁRIOS SEM ACESSO
        $userCDCNaoLista = \App\User::doesntHave('paycheck_access')->withoutGhosts()->approved()->orderBy('name')->get(['id','cpf','name']);
        $this->command->info("Usuários cadastrados no CDC Digital que não estão na lista: " . $userCDCNaoLista->count());

        $userCDCNaoLista_basename = 'reports/paycheck-access/users-in-cdc-not-on-list';
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
        $userNaListaNaoCDC = \App\PaycheckAccess::whereNull('user_id')->get();
        $this->command->info("Usuários na lista que não estão no CDC Digital: " . $userNaListaNaoCDC->count());
        $userNaListaNaoCDC_basename = 'reports/paycheck-access/users-on-list-not-in-cdc';
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
