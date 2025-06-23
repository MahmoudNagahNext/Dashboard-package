<?php

namespace nextdev\nextdashboard\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use nextdev\nextdashboard\Events\TicketReplied;
use nextdev\nextdashboard\Listeners\SendTicketReplyNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        TicketReplied::class => [
            SendTicketReplyNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}