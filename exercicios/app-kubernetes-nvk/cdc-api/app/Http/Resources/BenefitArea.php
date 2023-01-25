<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BenefitArea extends JsonResource
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
            'benefits' => Benefit::collection($this->whenLoaded('benefits')),
            'division' => (new BenefitDivision($this->whenLoaded('division')))
            // $this->mergeWhen(isset($this->divisions_count), [
            //     'divisions_count' => $this->divisions_count
            // ])
        ];
    }
}
