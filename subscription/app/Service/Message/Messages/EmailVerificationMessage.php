<?php

namespace App\Service\Message\Messages;

use App\Service\Message\KafkaMessageWrapper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

readonly class EmailVerificationMessage extends KafkaMessageWrapper
{

    public function __construct(
        private string $email
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
        return [
            'eventId' => Uuid::uuid4(),
            'eventType' => $this->getType(),
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => [
                'to' => $this->email,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'mail.VerificationNotification';
    }

    public function getTopic(): string
    {
        return 'mail-requests';
    }
}
