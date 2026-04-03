<?php

namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Http\Contracts\CommentInterface;
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

    public function getByTicket($ticket_id, $user)
    {
        return $this->repository->getByTicket($ticket_id, $user);
    }

    public function create(array $data)
    {
        $data['author_initials'] = strtoupper(substr($data['body'], 0, 2));

        return $this->repository
            ->create($data)
            ->load('author');
    }
}
