<?php 

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Mail\Mailable;

class NewTicketCreatedMail extends Mailable
{
    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('New Ticket Created')
            ->view('emails.ticket_created')
            ->with(['ticket' => $this->ticket]);
    }
}