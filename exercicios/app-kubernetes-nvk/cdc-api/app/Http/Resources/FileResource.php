<?php

namespace App\Http\Resources;

use App\FileSignature;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class FileResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->path,
            'accepted' => $this->accepted,
            'deadline' => (new DateTime($this->deadline))->format('d/m/Y H:i'),
            'status' => $this->status,
            'hashcode' => $this->hashcode,
            'created_at' => (new DateTime($this->created_at))->format('d/m/Y H:i'),
            'signature' => new FileSignatureResource($this->signature),
            'forHumans' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),
            'user' => new UserSimplified($this->user),
            'to' => UserSimplified::collection($this->users),
            'view_url' => URL::signedRoute('file.view', $this->id),
            'folders' => $this->folders
        ];
    }
}
