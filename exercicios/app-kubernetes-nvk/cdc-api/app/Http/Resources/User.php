<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'cpf' => $this->cpf,
            'registration' => $this->registration,
            'mobile' => $this->mobile,
            'avatar' => $this->avatar,
            'city_id' => $this->city_id,
            'city' => $this->city,
            'team_id' => $this->team_id,
            'approved' => (string) $this->approved,
            'allow_terms' => (string) $this->allow_terms,
            'first_time' => (string) $this->first_time,
            'team' => $this->team,
            'created_at' => (string) $this->created_at,
            'roles' => $this->roles,
            'workplace' => $this->workplace,
            'birth_date' => $this->birth_date,
            'received_notification_birthday' => $this->received_notification_birthday,
            'vcard_enable' => $this->vcard_enable
        ];
    }
}
