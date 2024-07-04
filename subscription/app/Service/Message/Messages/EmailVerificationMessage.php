<?php

namespace App\Service\Message\Messages;

use App\Enum\ConfigSpaceName;
use App\Notifications\VerifyEmailQueued;
use App\Service\Message\KafkaMessageWrapper;
use App\Utils\Utilities;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

readonly class EmailVerificationMessage extends KafkaMessageWrapper
{
    private const SUBJECT_STRING = 'Verify Email Address';
    private const FIRST_LINE_STRING = 'Please click the button below to verify your email address.';
    private const ACTION_STRING = 'Verify Email Address';

    public function __construct(
        private string $email,
        private Utilities $utilities
    ) {
        parent::__construct();

        $this->setPayload($this->getPayloadArray());
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    private function getPayloadArray(): array
    {
        $subject = Lang::get(self::SUBJECT_STRING);
        $firstLine = Lang::get(self::FIRST_LINE_STRING);
        $action = Lang::get(self::ACTION_STRING);

        $mailMessageString = (new MailMessage())
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(
                /** @phpstan-ignore-next-line */
                Lang::get('Verify Email Address'),
                $this->utilities->getStringValueFromEnvVariable(
                    ConfigSpaceName::APP->value,
                    'emailConfirmationLink'
                ),
            )
            ->line(Lang::get('If you did not create an account, no further action is required.'))
            ->render()->toHtml();

        return [
            'from' => $this->utilities->getStringValueFromEnvVariable(
                ConfigSpaceName::APP->value,
                'mailFromAddress'
            ),
            'subject' => $subject,
            /** @phpstan-ignore-next-line */
            'text' => $firstLine . ' ' . $action,
            'to' => $this->email,
            'html' => $mailMessageString
        ];
    }
}
