<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;

class DueSoonMail extends Mailable
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ SLA Due Soon Warning',
        );
    }

    public function build()
    {
        return $this->view('emails.due_soon')
            ->subject('⚠️ SLA Due Soon – '.$this->ticket->reference_number)
            ->with([
                'ticket' => $this->ticket,
            ]);
    }
}
