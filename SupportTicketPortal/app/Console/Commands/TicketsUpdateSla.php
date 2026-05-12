<?php

namespace App\Console\Commands;

use App\Mail\DueSoonMail;
use App\Mail\ResponseBreachMail;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TicketsUpdateSla extends Command
{
    protected $signature = 'tickets:update-sla';

    protected $description = 'Update SLA status for tickets automatically and send alerts to all agents';

    public function handle(): int
    {
        $now = Carbon::now();

        // Only open or in_progress tickets
        $tickets = Ticket::whereIn('status', ['open', 'in_progress'])->get();
        $this->info($now->toDateTimeString()." Starting SLA update for {$tickets->count()} tickets...");

        // Get all agents
        $agents = User::whereHas('roles', fn ($q) => $q->where('name', 'agent'))->get();

        foreach ($tickets as $ticket) {

            // Response SLA (first reply)
            if ($ticket->response_due_at && ! $ticket->first_response_at) {

                // Response Due Soon: within 1 hour before due
                if ($now->gte($ticket->response_due_at->subHour()) && ! $ticket->response_notified_due_soon) {
                    foreach ($agents as $agent) {
                        if ($agent->email) {
                            Mail::to($agent->email)->queue(new DueSoonMail($ticket));
                            $this->line($now->toDateTimeString()." ⚠️ Response DUE SOON email queued for {$agent->email}");
                        }
                    }
                    $ticket->response_notified_due_soon = 1;
                    $ticket->save();
                }

                // Response Overdue
                if ($now->gt($ticket->response_due_at) && ! $ticket->response_notified_overdue) {
                    foreach ($agents as $agent) {
                        if ($agent->email) {
                            Mail::to($agent->email)->queue(new ResponseBreachMail($ticket));
                            $this->line($now->toDateTimeString()." ❌ Response OVERDUE email queued for {$agent->email}");
                        }
                    }
                    $ticket->response_notified_overdue = 1;
                    $ticket->response_breached = 1;
                    $ticket->response_breached_at = $now;
                    $ticket->response_status = 'overdue';
                    $ticket->save();
                }
            }

            // Resolution SLA (final resolution)
            if (
                $ticket->resolution_due_at &&
                ! $ticket->resolved_at &&
                ! in_array($ticket->status, ['resolved', 'closed'])
            ) {
                // Resolution Due Soon: within 1 hour before due
                if ($now->gte($ticket->resolution_due_at->subHour()) && ! $ticket->resolution_notified_due_soon) {
                    foreach ($agents as $agent) {
                        if ($agent->email) {
                            Mail::to($agent->email)->queue(new DueSoonMail($ticket));
                            $this->line($now->toDateTimeString()." ⚠️ Resolution DUE SOON email queued for {$agent->email}");
                        }
                    }
                    $ticket->resolution_notified_due_soon = 1;
                    $ticket->save();
                }

                // Resolution Overdue
                if ($now->gt($ticket->resolution_due_at) && ! $ticket->resolution_notified_overdue) {
                    foreach ($agents as $agent) {
                        if ($agent->email) {
                            Mail::to($agent->email)->queue(new ResponseBreachMail($ticket));
                            $this->line($now->toDateTimeString()." ❌ Resolution OVERDUE email queued for {$agent->email}");
                        }
                    }
                    $ticket->resolution_notified_overdue = 1;
                    $ticket->resolution_breached = 1;
                    $ticket->resolution_breached_at = $now;
                    $ticket->save();
                }

                // Update SLA status for resolution only
                $sla = 'on_track';
                if ($ticket->resolution_notified_due_soon) {
                    $sla = 'due_soon';
                }
                if ($ticket->resolution_notified_overdue) {
                    $sla = 'overdue';
                }
                if ($ticket->sla_status !== $sla) {
                    $ticket->sla_status = $sla;
                    $ticket->save();
                    $this->line($now->toDateTimeString()." Ticket #{$ticket->id} Resolution SLA status updated to '{$sla}'");
                }
            }
        }

        $this->info($now->toDateTimeString().' SLA and Response checks completed successfully.');

        return 0;
    }
}
