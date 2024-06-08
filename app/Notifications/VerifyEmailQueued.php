<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    private const SUBJECT_STRING = 'Verify Email Address';
    private const FIRST_LINE_STRING = 'Please click the button below to verify your email address.';
    private const ACTION_STRING = 'Verify Email Address';
    private const SECOND_LINE_STRING = 'If you did not subscribe for this exchange rate newsletter then no further action is required.';


    protected function buildMailMessage($url): MailMessage
    {
        $subject = Lang::get(self::SUBJECT_STRING);
        $firstLine = Lang::get(self::FIRST_LINE_STRING);
        $action = Lang::get(self::ACTION_STRING);
        $secondLine = Lang::get(self::SECOND_LINE_STRING);

        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject(is_array($subject) ? self::SUBJECT_STRING : $subject)
            ->line(is_array($firstLine) ? self::FIRST_LINE_STRING : $firstLine)
            ->action(is_array($action) ? self::ACTION_STRING : $action, $url)
            ->line(is_array($secondLine) ? self::SECOND_LINE_STRING : $secondLine);
    }
}
