<?php

namespace App\Notifications;

use App\Models\NotifiableInterface;
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
     * @param NotifiableInterface $notifiable
     * @return array<int, string>
     */
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param NotifiableInterface $notifiable
     * @return MailMessage
     */
    public function toMail(NotifiableInterface $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting("Hello, {$notifiable->getNotificationRefer()}")
            ->line("This is the exchange rate for USD to UAH {$this->exchangeRate}.")
            ->line('Thank you for subscribing!');
    }

    /**
     * @param NotifiableInterface $notifiable
     * @return array<string, Subscriber>
     */
    public function toArray(NotifiableInterface $notifiable): array
    {
        return [
            //
        ];
    }
}
