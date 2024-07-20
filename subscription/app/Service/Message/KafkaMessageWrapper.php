<?php

namespace App\Service\Message;

use Junges\Kafka\Message\Message;

abstract readonly class KafkaMessageWrapper implements MessageWrapperInterface
{
    protected Message $message;

    public function __construct()
    {
        $this->message = new Message();
    }

    public function setPayload(array $payload): MessageWrapperInterface
    {
        $this->message->withBody($payload);

        return $this;
    }

    public function setHeaders(array $headers): MessageWrapperInterface
    {
        $this->message->withHeaders($headers);

        return $this;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    abstract protected function getType(): string;

    abstract public function getTopic(): string;
}
