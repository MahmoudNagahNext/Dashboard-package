<?php

namespace nextdev\nextdashboard\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use nextdev\nextdashboard\Events\AdminCreated;
use nextdev\nextdashboard\Events\RoleAssignedToAdmin;
use nextdev\nextdashboard\Events\TicketAssigned;
use nextdev\nextdashboard\Events\TicketCreated;
use nextdev\nextdashboard\Events\TicketReplied;
use nextdev\nextdashboard\Listeners\SendAdminCreatedNotification;
use nextdev\nextdashboard\Listeners\SendRoleAssignedNotification;
use nextdev\nextdashboard\Listeners\SendTicketAssignedNotification;
use nextdev\nextdashboard\Listeners\SendTicketCreatedNotification;
use nextdev\nextdashboard\Listeners\SendTicketReplyNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        AdminCreated::class => [
            SendAdminCreatedNotification::class,
        ],
        RoleAssignedToAdmin::class => [
            SendRoleAssignedNotification::class,
        ],
        TicketCreated::class => [
            SendTicketCreatedNotification::class,
        ],
        TicketAssigned::class => [
            SendTicketAssignedNotification::class,
        ],
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