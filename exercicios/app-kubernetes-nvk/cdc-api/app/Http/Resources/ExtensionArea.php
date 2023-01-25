<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionArea extends JsonResource
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
            'numbers' => ExtensionNumber::collection($this->whenLoaded('numbers')),
            'division' => (new ExtensionDivision($this->whenLoaded('division')))
        ];
    }
}
