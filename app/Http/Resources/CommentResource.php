<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "userName" => $this->user->name,
            "userEmail" => $this->user->email,
            "feedBackTitle" => $this->feedback->title,
            "category" => $this->feedback->category,
            "content" => $this->content,
            "display" => false,
            "id" => $this->id,
        ];
    }
}
