<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $wrap = [
            "state" => $this[0]->state,
            "cities" => []
        ];

        for($i=0; $i < count($this); $i++) {
            $wrap['cities'][] = collect($this[$i])->except('state');
        }
        return $wrap;
    }
}
