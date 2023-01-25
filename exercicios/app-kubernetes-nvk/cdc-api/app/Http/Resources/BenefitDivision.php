<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BenefitDivision extends JsonResource
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
            'ionicon' => $this->ionicon,
            'areas' => BenefitArea::collection($this->whenLoaded('areas')),
            'benefits' => Benefit::collection($this->whenLoaded('benefits')),
            'orphan_benefits' => Benefit::collection($this->whenLoaded('benefitsWithNoArea'))
        ];
    }
}
