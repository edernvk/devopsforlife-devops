<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            'created_at' => (string) $this->created_at,
            'description' => $this->description,
            'forHumans' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
            'from' => $this->from,
            'fromData' => new UserSimplified($this->whenLoaded('fromUser')),
            'id' => $this->id,
            'publish_datetime' => $this->publish_datetime,
            'title' => $this->title,
            $this->mergeWhen(auth()->user()->hasRole('Administrador'), [
                'to' => UserSimplified::collection($this->whenLoaded('to')),
                'status_id' => $this->status_id,
            ]),
        ];
    }
}
