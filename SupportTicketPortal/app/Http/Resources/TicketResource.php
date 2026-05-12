<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'sla_status' => $this->sla_status,
            'priority' => [
                'id' => $this->priority?->id,
                'name' => $this->priority?->name,
                'slug' => $this->priority?->slug,
            ],
            'creator' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
                'email' => $this->creator?->email,
            ],
            'assignee' => $this->assignee ? [
                'id' => $this->assignee->id,
                'name' => $this->assignee->name,
                'email' => $this->assignee->email,
            ] : null,
            'response_due_at' => $this->response_due_at?->toDateTimeString(),
            'first_response_at' => $this->first_response_at?->toDateTimeString(),
            'response_breached' => (bool) $this->response_breached,
            'response_breached_at' => $this->response_breached_at?->toDateTimeString(),
            'resolution_due_at' => $this->resolution_due_at?->toDateTimeString(),
            'resolved_at' => $this->resolved_at?->toDateTimeString(),
            'resolution_breached' => (bool) $this->resolution_breached,
            'resolution_breached_at' => $this->resolution_breached_at?->toDateTimeString(),
            'comments' => $this->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'author_id' => $comment->author_id,
                    'body' => $comment->body,
                    'visibility' => $comment->visibility,
                    'created_at' => $comment->created_at?->toDateTimeString(),
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
