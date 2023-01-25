<?php

namespace App\Services;

use App\CupShirtCampaing;
use App\Exports\ReportExport;

class UpdatersCupShirtCampaingReport
{
    public function generateEntriesReport() {
        $pedidos = CupShirtCampaing::with(['user' => function ($query) {
            $query->whereIn('cpf', [
                '99999999991',
                "42886096822",
                "38073335840",
                "46483745842",
                "21888134801",
                "41882371801",
                "37801034864",
                "31672510856",
                "46285209847",
                "31447224809",
                "50453842879",
                "47506236877",
                "25932425822",
                "43631372825",
            ]);
        }])
        ->whereHas('user', function ($query) {
            $query->whereIn('cpf', [
                '99999999991',
                "42886096822",
                "38073335840",
                "46483745842",
                "21888134801",
                "41882371801",
                "37801034864",
                "31672510856",
                "46285209847",
                "31447224809",
                "50453842879",
                "47506236877",
                "25932425822",
                "43631372825",
            ]);
        })
        ->withCount('products')
        ->get();

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
        $pedidos = CupShirtCampaing::with(['user' => function ($query) {
            $query->whereIn('cpf', [
                '99999999991',
                "42886096822",
                "38073335840",
                "46483745842",
                "21888134801",
                "41882371801",
                "37801034864",
                "31672510856",
                "46285209847",
                "31447224809",
                "50453842879",
                "47506236877",
                "25932425822",
                "43631372825",
            ]);
        }])
        ->whereHas('user', function ($query) {
            $query->whereIn('cpf', [
                '99999999991',
                "42886096822",
                "38073335840",
                "46483745842",
                "21888134801",
                "41882371801",
                "37801034864",
                "31672510856",
                "46285209847",
                "31447224809",
                "50453842879",
                "47506236877",
                "25932425822",
                "43631372825",
            ]);
        })->get();

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
