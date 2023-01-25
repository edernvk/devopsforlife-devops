<?php

namespace App\Services;

use App\CupShirtCampaing;
use App\Exports\ReportExport;

class CupShirtCampaingReport
{
    public function generateEntriesReport() {
        $pedidos = CupShirtCampaing::with(['user'])->withCount('products')->get();

        $basename = $this->generateFileBasename(now());
        $headers = [
            'NOME',
            'CPF',
            'TOTAL_PEDIDOS',
            'QTD_PARCELAS',
            'ACEITO_EM'
        ];

        $entries = [];

        foreach($pedidos as $item) {
            $row = [];

            $row[] = $item->user->name;
            $row[] = (string) $item->user->cpf;
            $row[] = $item->total_amount;
            $row[] = $item->installments_amount;
            $row[] = $item->payment_agreement;

            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generatePedidosPorUsuarioReport() {
        $pedidos = CupShirtCampaing::with(['user'])->get();

        $basename = $this->generateFileBasename(now());
        $headers = [
            'NOME',
            'CPF',
            'CAMISETA',
            'QTD_PEDIDOS',
            'TAMANHO',
        ];

        $entries = [];

        foreach($pedidos as $item) {
            foreach ($item->products as $product) {
                $row = [];

                $row[] = $item->user->name;
                $row[] = (string) $item->user->cpf;
                $row[] = $product->name;
                $row[] = $product->pivot->amount;
                $row[] = $product->pivot->size;

                $entries[] = $row;
            }
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }

    public function generateFileBasename($date) {
        return 'reports/cup-shirt/'.$date->format('Y-m-d-His').'-entries';
    }
}
