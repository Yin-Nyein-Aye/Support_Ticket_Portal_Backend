<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'author_id' => $this->author_id,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'parent_comment_id' => $this->parent_comment_id,
            'author' => new UserResource(
                $this->whenLoaded('author')
            ),
            'replies' => CommentResource::collection(
                $this->whenLoaded('replies')
            ),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
