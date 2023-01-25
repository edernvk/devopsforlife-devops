<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user' => [
                'name' => $this->commentUser->name
             ],
            'content' => $this->content,
            'status' => $this->status ? 'Postado':'Não Postado',
            'user_id' => $this->user_id,
            'newsletter_news_id' => $this->newsletter_news_id,
            'newsletter' => [
                 'title' => $this->commentNewsletter->title
            ],
            'created_at' => (string) $this->created_at,
        ];
    }
}
