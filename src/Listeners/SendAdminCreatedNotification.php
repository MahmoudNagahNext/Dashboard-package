<?php

namespace nextdev\nextdashboard\Listeners;

use nextdev\nextdashboard\Events\AdminCreated;
use nextdev\nextdashboard\Notifications\AdminCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use nextdev\nextdashboard\Models\Admin;

class SendAdminCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(AdminCreated $event): void
    {
        // Notify all other admins about the new admin
        $admins = Admin::where('id', '!=', $event->admin->id)->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminCreatedNotification($event->admin));
        }
    }
}