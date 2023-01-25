<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaycheckAccess extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "email" => $this->email,
            "password" => $this->password,
            "cpf" => $this->cpf,
            "user" => [
                "name" => $this->user->name,
                "cpf" => $this->user->cpf,
                "avatar" => $this->user->avatar
            ]
        ];
    }
}
