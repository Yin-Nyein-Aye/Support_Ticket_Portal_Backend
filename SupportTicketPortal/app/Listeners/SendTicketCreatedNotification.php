<?php
// app/Listeners/SendTicketCreatedNotification.php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicketCreatedMail;

class SendTicketCreatedNotification
{
    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        $agents = User::whereHas('roles', fn($q) => $q->where('name', 'agent'))->get();

        foreach ($agents as $agent) {
            if ($agent->email) {
                Mail::to($agent->email)
                    ->queue(new NewTicketCreatedMail($ticket));
            }
        }
    }
}
