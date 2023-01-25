<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/* 2021-03-30 */
/* DEPRECATED IN FAVOR OF MessageMinimalistCollection */
class OptimizedMessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
//            'id' => $this->id,
//            'title' => $this->title,
//            'description' => $this->description,
//            'created_at' => (string) $this->created_at,
//            'forHumans' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
//            'publish_datetime' => $this->publish_datetime,
//            'status_id' => $this->status_id,
//            'current_user_read' => (string) $this->to[$this->to->search(function ($item, $key) {
//                    return $item->pivot->user_id == Auth::user()->id;
//                })]->pivot->read ?? null,
//            'from' => $this->from,
//            'avatar' => $this->fromUser->avatar,
//            'name' => $this->fromUser->name,
        ];
    }
}
