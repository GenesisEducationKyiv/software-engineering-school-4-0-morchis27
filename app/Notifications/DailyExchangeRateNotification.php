<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyExchangeRateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private float $exchangeRate;

    public function __construct(float $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @param Subscriber $notifiable
     * @return array<int, string>
     */
    public function via(Subscriber $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(Subscriber $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting("Hello, $notifiable->email")
            ->line("This is the exchange rate for USD to UAH {$this->exchangeRate}.")
            ->line('Thank you for subscribing!');
    }

    /**
     * @param Subscriber $notifiable
     * @return array<string, Subscriber>
     */
    public function toArray(Subscriber $notifiable): array
    {
        return [
            //
        ];
    }
}
