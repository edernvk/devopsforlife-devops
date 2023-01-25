<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSimplified extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'registration' => $this->registration,
            'avatar' => $this->avatar,
            'team_id' => $this->team_id,
            'workplace' => $this->workplace,
            'approved' => (string) $this->approved,
            'vcard_enable' => $this->vcard_enable

        ];
    }
}
