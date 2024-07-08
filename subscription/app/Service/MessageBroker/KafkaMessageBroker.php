<?php

namespace App\Service\MessageBroker;

use App\Service\Message\MessageWrapperInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;

readonly class KafkaMessageBroker implements MessageBrokerInterface
{
    public function __construct(
        private string $kafkaHost,
        private string $topic,
    ) {
    }

    /**
     * @throws Exception
     */
    public function publish(MessageWrapperInterface $messageWrapper): void
    {
        Log::info($this->kafkaHost);
        Log::info($this->topic);

        Kafka::publish($this->kafkaHost)->onTopic($this->topic)
            ->withMessage($messageWrapper->getMessage())->send();
    }
}
