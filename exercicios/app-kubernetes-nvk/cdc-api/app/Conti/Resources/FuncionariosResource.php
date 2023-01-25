<?php

namespace Conti\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FuncionariosResource extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'cpf' => $this->cpf,
            'name' => $this->nome_funcio
        ];
    }
}
