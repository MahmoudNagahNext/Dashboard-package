<?php

namespace nextdev\nextdashboard\Listeners;

use nextdev\nextdashboard\Events\RoleAssignedToAdmin;
use nextdev\nextdashboard\Notifications\RoleAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use nextdev\nextdashboard\Models\Admin;

class SendRoleAssignedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(RoleAssignedToAdmin $event): void
    {
        // Notify all admins about the role assignment
        $admins = Admin::all();
        
        foreach ($admins as $admin) {
            $admin->notify(new RoleAssignedNotification($event->admin, $event->role));
        }
    }
}