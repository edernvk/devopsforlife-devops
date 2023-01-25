<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthDocs extends JsonResource
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
            'title' => $this->title,
            'url_doc' => $this->url_doc,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'created_at' => (string) $this->created_at
        ];
    }
}
