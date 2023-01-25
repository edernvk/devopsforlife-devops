<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ExtensionArea as ExtensionAreaModel;
use App\ExtensionDivision as ExtensionDivisionModel;

class ExtensionNumber extends JsonResource
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
            'number' => $this->number,
            $this->mergeWhen($this->relationLoaded('parentable'), function() {
                $division = null;
                $area = null;

                $division = null;
                $area = null;

                if ($this->parentable_type === ExtensionAreaModel::class) {
                    $division = $this->parentable->division;
                    unset($this->parentable->division);
                    $area = $this->parentable;
                } else if ($this->parentable_type === ExtensionDivisionModel::class) {
                    $division = $this->parentable;
                }

                return [
                    'division' => (new ExtensionDivision($division)),
                    'area' => $this->when($area, (new ExtensionArea($area)))
                ];
            })
        ];
    }
}
