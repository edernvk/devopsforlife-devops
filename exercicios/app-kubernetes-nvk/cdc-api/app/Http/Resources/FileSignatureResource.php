<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class FileSignatureResource extends JsonResource
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
            'file' => $this->file,
            'user' => new UserSimplified($this->user),
            'sing' => $this->sing,
            'url' => $this->fileurl,
            'created_at' => (new DateTime($this->created_at))->format('d/m/Y H:i'),
        ];
    }
}
