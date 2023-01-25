<?php

namespace App\Services;

use App\BurguesaJacketCampaign;
use App\Exports\ReportExport;

class BurguesaJacketCampaignReport
{
    public function generateEntriesReport() {
        $pedidos = BurguesaJacketCampaign::with('user')->get();
        
        $basename = $this->generateFileBasename(now());
        $headers = [
            'nome',
            'cpf',
            'tamanho_1',
            'tamanho_2',
            'qtd_pedido',
            'qtd_parcelas',
            'aceito_em'
        ];

        $entries = [];

        foreach($pedidos as $item) {
            $row = [];

            $row[] = $item->user->name;
            $row[] = (string) $item->user->cpf;
            $row[] = $item->jacket_1_size;
            $row[] = $item->jacket_1_size ?? '-';
            $row[] = ($item->jacket_2_size) ? 2 : 1;
            $row[] = $item->installments_amount;
            $row[] = $item->payment_agreement;
            
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/burguesa-jacket/'.$date->format('Y-m-d-His').'-entries';
    }

}
