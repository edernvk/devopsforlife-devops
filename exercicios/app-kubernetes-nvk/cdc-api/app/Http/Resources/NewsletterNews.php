<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterNews extends JsonResource
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
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'contrast' => $this->contrast,
            'commentable' =>$this->commentable,
            'status_id' => $this->status_id,
            'publish_datetime' => $this->publish_datetime,
            'user' => new UserSimplified($this->user),
            'is_active' => $this->is_active,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s')
        ];
    }
}
