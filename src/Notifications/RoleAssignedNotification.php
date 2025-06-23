<?php

namespace nextdev\nextdashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use nextdev\nextdashboard\Models\Admin;
use Spatie\Permission\Models\Role;

class RoleAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Admin $admin;
    protected Role $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(Admin $admin, Role $role)
    {
        $this->admin = $admin;
        $this->role = $role;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Role Assigned to Admin")
            ->line("The role {$this->role->name} has been assigned to admin {$this->admin->name}.")
            ->action('View Admin', url("/dashboard/admins/{$this->admin->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'admin_id' => $this->admin->id,
            'admin_name' => $this->admin->name,
            'role_id' => $this->role->id,
            'role_name' => $this->role->name,
            'message' => "Role {$this->role->name} assigned to admin {$this->admin->name}",
        ];
    }
}