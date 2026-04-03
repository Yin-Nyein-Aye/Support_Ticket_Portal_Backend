<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\User;
use App\Mail\ResponseBreachMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendResponseBreachEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle(): void
    {
        $agents = User::role('agent')->get();

        foreach ($agents as $agent) {
            if (!$agent->email) continue;

            Mail::to($agent->email)
                ->send(new ResponseBreachMail($this->ticket));
        }
    }
}