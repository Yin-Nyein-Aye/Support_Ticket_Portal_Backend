<?php

namespace App\Http\Contracts;

interface CommentInterface extends BaseInterface
{
    public function getByTicketId($ticket_id, $user);
    public function getTicket($ticket_id);
}
