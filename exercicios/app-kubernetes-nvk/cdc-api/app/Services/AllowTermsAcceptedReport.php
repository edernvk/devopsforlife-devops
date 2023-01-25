<?php


namespace App\Services;


use App\Exports\ReportExport;

class AllowTermsAcceptedReport
{

    public function generateEntriesReport(array $cpfs) {

        // selecionar usuários para verificar allow-terms
        // incluir apenas não-fantasmas

        $users = \App\User::select('name', 'cpf', 'approved', 'allow_terms')
            ->withoutGhosts()
            ->whereIn('cpf', $cpfs)
            ->orderBy('name')
            ->get();

        // dump('Usuários selecionados: '. $users->count());

        $basename = $this->generateFileBasename(now());
        $headers = [
            'nome',
            'cpf',
            'aprovado_em',
            'termos_aceito_em'
        ];

        $entries = [];

        foreach($users as $item) {
            $row = [];
            $row[] = $item['name'];
            $row[] = (string) $item['cpf'];
            $row[] = $item['approved'] ?? 'false';
            $row[] = $item['allow_terms'] ?? 'false';
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateAllUsersReport() {

        // selecionar usuários para verificar allow-terms
        // incluir apenas não-fantasmas

        $users = \App\User::select('name', 'cpf', 'approved', 'allow_terms')
            ->withoutGhosts()
            ->orderBy('name')
            ->get();

        // dump('Usuários selecionados: '. $users->count());

        $basename = $this->generateFileBasename(now());
        $headers = [
            'nome',
            'cpf',
            'aprovado_em',
            'termos_aceito_em'
        ];

        $entries = [];

        foreach($users as $item) {
            $row = [];
            $row[] = $item['name'];
            $row[] = (string) $item['cpf'];
            $row[] = $item['approved'] ?? 'false';
            $row[] = $item['allow_terms'] ?? 'false';
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/allow-terms/'.$date->format('Y-m-d-Hi').'-users';
    }

    public function generateAllowTermsAcceptedDownloadName($date) {
        return 'cdcdigital-termos-de-uso-aceitos-'. $date->format('Y-m-d-Hi') ;
    }
}
