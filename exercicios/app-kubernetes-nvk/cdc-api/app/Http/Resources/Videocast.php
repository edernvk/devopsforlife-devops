<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Videocast extends JsonResource
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
            'description' => $this->description,
            'video_url' => $this->when($this->trackeable !== true, $this->video_url),
            'date' => (string) $this->date,
            'trackeable' => $this->trackeable
        ];
    }
}
