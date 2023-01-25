<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageMinimalistCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => (string) $this->created_at,
            'forHumans' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
            'publish_datetime' => $this->publish_datetime,
            'status_id' => $this->status_id,
            'current_user_read' => $this->current_user_read,
            'from' => $this->from,
            'fromData' => new UserSimplified($this->whenLoaded('fromUser')),
            $this->mergeWhen($this->whenLoaded('fromUser'), [
                'name' => $this->fromUser->name ?? null,
                'avatar' => $this->fromUser->avatar ?? null,
            ]),
//            'current_user_read' => $this->when($this->whenLoaded('to'), ($index = $this->to->search(function ($item) {
//                return $item->id == auth()->user()->id;
//            })) ? $this->to[$index]->pivot->read : ''),
//            $this->mergeWhen(auth()->user()->id == $this->from, [
//                'to' => UserSimplified::collection($this->whenLoaded('to'))
//            ]),
        ];
    }
}

/**
 * __First mark of stupidity:__
 * 'current_user_read' => (string) $this->to[$this->to->search(function ($item, $key) {
 *      return $item->id == $this->id;
 * })]->pivot->read,
 *
 * instead of:
 *
 * 'current_user_read' => (string) $this->to->find($this->id)->pivot->read
 *
 * $this->whenPivotLoaded('role_user', function () {
 *      return $this->pivot->expires_at;
 * }),
 */
