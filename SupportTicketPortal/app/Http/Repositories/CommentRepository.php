<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Http\Contracts\CommentInterface;
use App\Models\TicketComment;


class CommentRepository extends BaseRepository implements CommentInterface
{
    public function __construct(TicketComment $model)
    {
        parent::__construct($model);
    }

    public function getByTicket($ticket_id, $user)
    {
        $query = $this->model
            ->with('author')
            ->where('ticket_id', $ticket_id)
            ->oldest('created_at'); // FIFO

        if (!$user->hasRole('agent')) {
            $query->where('visibility', 'public');
        }

        return $query->get();
    }
}
