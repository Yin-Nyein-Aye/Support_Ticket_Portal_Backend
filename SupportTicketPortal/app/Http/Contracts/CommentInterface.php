<?php

namespace App\Http\Contracts;

use App\Http\Contracts\BaseInterface;

interface CommentInterface extends BaseInterface
{
    public function getByTicket($ticket_id, $user);
}
