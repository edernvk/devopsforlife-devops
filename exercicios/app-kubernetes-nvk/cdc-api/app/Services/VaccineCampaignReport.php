<?php

namespace App\Services;

use App\Exports\ReportExport;
use App\VaccineCampaing;

class VaccineCampaignReport
{
    public function generateReport()
    {
        $vaccineCampaing = VaccineCampaing::with('user:id,name,cpf')->get();

        $basename = $this->generateFileBasename(now());
        $headers = [
            'nome_completo',
            'cpf',
            'confirmado',
            'autorizado',
            'preenchido_em'
        ];

        $entries = [];
        foreach ($vaccineCampaing as $item) {
            $row = [];

            $row[] = $item->user->name;
            $row[] = (string) $item->user->cpf;
            $row[] = $item->confirmation;
            $row[] = $item->authorize === true ? 'SIM' : 'NAO';
            $row[] = $item->created_at;

            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    private function generateFileBasename($date) {
        return 'reports/vaccine-campaign/'.$date->format('Y-m-d-His');
    }
}
