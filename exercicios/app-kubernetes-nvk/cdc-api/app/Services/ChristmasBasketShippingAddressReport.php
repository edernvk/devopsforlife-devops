<?php
namespace App\Services;

use App\ChristmasBasket;
use App\Exports\ReportExport;

class ChristmasBasketShippingAddressReport 
{
    public function generateReport()
    {
        $christmasBaskets = ChristmasBasket::with('user:id,name,cpf')->get();

        $basename = $this->generateFileBasename(now());
        $headers = [
            'colaborador_cpf',
            'colaborador_nome',
            'nome_do_destinatario',
            'grau_de_parentesco_do_destinatario',
            'rua',
            'numero',
            'bairro',
            'cep',
            'cidade',
            'preenchido_em',
            'complemento',
            'sugestao_de_melhoria'
        ];

        $entries = [];
        foreach ($christmasBaskets as $christmasBasket) {
            $row = [];

            $row[] = $christmasBasket->user->cpf;
            $row[] = $christmasBasket->user->name;
            $row[] = $christmasBasket->name_recipient;
            $row[] = $christmasBasket->degree_kinship;
            $row[] = $christmasBasket->shipping_address_street_name;
            $row[] = $christmasBasket->shipping_address_number;
            $row[] = $christmasBasket->shipping_address_neighbourhood;
            $row[] = $christmasBasket->shipping_address_zipcode;
            $row[] = $christmasBasket->shipping_address_city;
            $row[] = $christmasBasket->created_at;
            $row[] = $christmasBasket->shipping_address_complement !== null ? $christmasBasket->shipping_address_complement : '_';
            $row[] = $christmasBasket->suggestion !== null ? $christmasBasket->suggestion : '_';
            
            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }
    
    private function generateFileBasename($date) {
        return 'reports/christmas-basket/'.$date->format('Y-m-d-His').'-shipping-address';
    }
}