<?php

namespace App\Http\Repositories;

use App\Http\Contracts\CommentInterface;
use App\Models\Ticket;
use App\Models\TicketComment;

class CommentRepository extends BaseRepository implements CommentInterface
{
    public function __construct(TicketComment $model)
    {
        parent::__construct($model);
    }

    public function getByTicketId($ticket_id, $user)
    {
        $query = $this->model
            ->with(['author', 'replies'])
            ->where('ticket_id', $ticket_id)
            ->whereNull('parent_comment_id')
            ->oldest('created_at');

        if (! $user->hasRole('agent')) {
            $query->where('visibility', 'public');
        }

        return $query->get();
    }

    public function getTicket($ticket_id) {
        return Ticket::findOrFail($ticket_id);
    }
}
