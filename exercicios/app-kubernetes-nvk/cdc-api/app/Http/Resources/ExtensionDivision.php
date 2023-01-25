<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionDivision extends JsonResource
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
            'color' => $this->color,
            'areas' => ExtensionArea::collection($this->whenLoaded('areas')),
            'numbers' => ExtensionNumber::collection($this->whenLoaded('numbers')),
            'orphan_numbers' => ExtensionNumber::collection($this->whenLoaded('numbersWithNoArea'))
        ];
    }
}
