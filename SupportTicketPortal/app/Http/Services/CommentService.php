<?php

namespace App\Http\Services;

use App\Http\Contracts\CommentInterface;
use App\Models\Ticket;
use App\Models\TicketComment; // ← you need this import

class CommentService extends BaseService
{
    public function __construct(CommentInterface $repository)
    {
        parent::__construct($repository);
    }

    public function model(): string
    {
        return TicketComment::class;
    }

    public function create(array $data)
    {
        $data['author_initials'] = strtoupper(substr($data['body'], 0, 2));

        return $this->repository
            ->create($data)
            ->load('author');
    }

    public function getCommentsForUser($ticketId, $user)
    {
        if ($user->hasRole('agent')) {
            return $this->repository->getByTicketId($ticketId, $user);
        }

        if ($user->hasRole('client')) {
            $ticket = Ticket::with('creator')->findOrFail($ticketId);

            if ($ticket->creator->organisation_id === $user->organisation_id) {
                return $this->repository->getByTicketId($ticketId, $user);
            }

            return collect([]);
        }

        return collect([]);
    }

    public function getTicket($ticket_id) {
        return $this->repository->getTicket($ticket_id);
    }
}
