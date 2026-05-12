<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\TicketCreated::class => [
            \App\Listeners\SendTicketCreatedNotification::class,
        ],

        \App\Events\TicketAssigned::class => [
            \App\Listeners\SendTicketAssignedNotification::class,
        ],
    ];
}
