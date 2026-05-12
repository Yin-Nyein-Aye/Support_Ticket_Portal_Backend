<?php

// app/Listeners/SendTicketAssignedNotification.php

namespace App\Listeners;

use App\Events\TicketAssigned;
use App\Mail\TicketAssignedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketAssignedNotification
{
    public function handle(TicketAssigned $event): void
    {
        $ticket = $event->ticket;

        if ($ticket->creator && $ticket->creator->email) {
            Mail::to($ticket->creator->email)
                ->queue(new TicketAssignedMail($ticket));
        }
    }
}
