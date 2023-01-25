<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVideocastTracked extends JsonResource
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
            'id' => $this->videocast->id,
            'video_url' => $this->videocast->video_url,
            'participation' => (string) $this->participation
        ];
    }
}
