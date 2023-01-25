<?php

namespace App\Http\Resources;

use App\BenefitArea as BenfitAreaModel;
use App\BenefitDivision as BenefitDivisionModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Benefit extends JsonResource
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
            'partner' => $this->partner,
            'contact' => $this->contact,
            'benefit' => $this->benefit,
            $this->mergeWhen($this->relationLoaded('parentable'), function () {
                $division = null;
                $area = null;

                $division = null;
                $area = null;

                if ($this->parentable_type === BenfitAreaModel::class) {
                    $division = $this->parentable->division;
                    unset($this->parentable->division);
                    $area = $this->parentable;
                } else if ($this->parentable_type === BenefitDivisionModel::class) {
                    $division = $this->parentable;
                }

                return [
                    'division' => (new BenefitDivision($division)),
                    'area' => $this->when($area, (new BenefitArea($area)))
                ];
            })
        ];
    }
}
