<?php
namespace App\Services;

use App\ChristmasBasket;
use App\Exports\ReportExport;

class ChristmasBasketUpdateAddressReport 
{
    public function generateReport()
    {
        $christmasBaskets = ChristmasBasket::select([
            'name',
            'phone',
            'email',
            'address_street_name',
            'address_number',
            'address_neighbourhood',
            'address_zipcode',
            'address_state',
            'address_city',
            'address_complement',
        ])->get();

        $basename = $this->generateFileBasename(now());
        $headers = [
            'nome_completo',
            'telefone',
            'email',
            'rua',
            'numero',
            'bairro',
            'cep',
            'estado',
            'cidade',
            'complemento',
        ];

        $entries = [];

        foreach ($christmasBaskets as $christmasBasket) {
            $row = [];

            $row[] = $christmasBasket->name;
            $row[] = $christmasBasket->phone;
            $row[] = $christmasBasket->email;
            $row[] = $christmasBasket->address_street_name;
            $row[] = $christmasBasket->address_number;
            $row[] = $christmasBasket->address_neighbourhood;
            $row[] = $christmasBasket->address_zipcode;
            $row[] = $christmasBasket->address_state;
            $row[] = $christmasBasket->address_city;
            $row[] = $christmasBasket->address_complement !== null ? $christmasBasket->address_complement : '_' ;

            $entries[] = $row;
        }

        return ReportExport::generateCsv($basename, $headers, $entries);
    }
    
    private function generateFileBasename($date) {
        return 'reports/christmas-basket/'.$date->format('Y-m-d-His').'-updated-address';
    }
}