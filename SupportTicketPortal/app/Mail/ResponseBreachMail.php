<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;

class ResponseBreachMail extends Mailable
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 SLA Response Breach Alert',
        );
    }

    public function build()
    {
        return $this->view('emails.response_breach')
            ->subject('🚨 SLA Breached – ' . $this->ticket->reference_number)
            ->with([
                'ticket' => $this->ticket,
            ]);
    }
}