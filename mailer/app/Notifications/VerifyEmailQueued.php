<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailQueued extends Notification
{
    private const SUBJECT_STRING = 'Verify Email Address';
    private const FIRST_LINE_STRING = 'Please click the button below to verify your email address.';
    private const ACTION_STRING = 'Verify Email Address';
    // phpcs:ignore
    private const SECOND_LINE_STRING = 'If you did not subscribe for this exchange rate newsletter then no further action is required.';

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable)
    {
        return (new MailMessage())
            ->subject(self::SUBJECT_STRING)
            ->line(self::FIRST_LINE_STRING)
            ->action(self::ACTION_STRING, 'https://example.com')
            ->line(self::SECOND_LINE_STRING);
    }
}
