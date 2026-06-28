<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Quote;

class NewQuoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Quote $quote) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New Quote Received')
            ->line('You received a new quote for: ' . $this->quote->jobRequest->title)
            ->line('Price: $' . $this->quote->price)
            ->action('View Quote', route('user.quotes.show', $this->quote))
            ->line('Thank you for using Skillmap!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'quote_id' => $this->quote->id,
            'job_title' => $this->quote->jobRequest->title,
            'price' => $this->quote->price,
        ];
    }
}
