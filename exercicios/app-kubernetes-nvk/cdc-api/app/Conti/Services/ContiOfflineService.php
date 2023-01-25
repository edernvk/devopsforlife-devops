<?php

namespace Conti\Services;

use Conti\Interfaces\ContiInterface;
use Illuminate\Support\Facades\File;

class ContiOfflineService implements ContiInterface
{

    private function loadData() {
        $json = File::get(app_path('Conti/data/cdc-funcionarios.json'));
        return collect(json_decode($json, true));
    }

    public function getFuncionarios()
    {
        $data = $this->loadData();
        return $data;
    }

    public function getFuncionario($userCpf)
    {
        // TODO: Implement getFuncionario() method.
    }
}
