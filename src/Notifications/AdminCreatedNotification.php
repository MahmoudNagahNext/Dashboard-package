<?php

namespace nextdev\nextdashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use nextdev\nextdashboard\Models\Admin;

class AdminCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Admin $admin;

    /**
     * Create a new notification instance.
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
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
            ->subject("New Admin Created")
            ->line("A new admin has been created: {$this->admin->name}")
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
            'admin_email' => $this->admin->email,
            'message' => "New admin created: {$this->admin->name}",
        ];
    }
}