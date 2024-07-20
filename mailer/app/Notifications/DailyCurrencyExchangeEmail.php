<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyCurrencyExchangeEmail extends Notification
{
    private float $exchangeRate;

    public function __construct(float $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting("Hello, {$notifiable}")
            ->line("This is the exchange rate for USD to UAH {$this->exchangeRate}.")
            ->line('Thank you for subscribing!');
    }

    /**
     * @return array<string, Subscriber>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
