<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskerVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $status,
        public string $reason = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = new MailMessage();
        $message->subject('Skillmap - Tasker Verification Update');

        if ($this->status === 'approved') {
            $message->greeting('Great news!')
                ->line('Your tasker profile has been approved!')
                ->line('You can now start receiving job requests.');
        } else {
            $message->greeting('Profile Under Review')
                ->line('Your tasker profile was not approved.')
                ->line('Reason: ' . $this->reason)
                ->line('Please review and resubmit.');
        }

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }
}
