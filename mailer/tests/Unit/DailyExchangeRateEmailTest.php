<?php

namespace Tests\Unit;

use App\Notifications\DailyCurrencyExchangeEmail;
use Illuminate\Testing\PendingCommand;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;

class DailyExchangeRateEmailTest extends TestCase
{
    public function testVerificationEmailSent(): void
    {
        Notification::fake();
        Kafka::fake();

        Kafka::shouldReceiveMessages([
            new ConsumedMessage(
                topicName: 'daily-mail-requests',
                partition: 0,
                headers: [],
                body: ['data' => ['to' => 'test1@example.com']],
                key: null,
                offset: 0,
                timestamp: 0
            ),
            new ConsumedMessage(
                topicName: 'daily-mail-requests',
                partition: 0,
                headers: [],
                body: ['data' => ['to' => 'test2@example.com']],
                key: null,
                offset: 0,
                timestamp: 0
            ),
            new ConsumedMessage(
                topicName: 'daily-mail-requests',
                partition: 0,
                headers: [],
                body: ['data' => ['to' => 'test3@example.com']],
                key: null,
                offset: 0,
                timestamp: 0
            ),
        ]);

        $commandExecutionResult = $this->artisan('app:send-email');

        if ($commandExecutionResult instanceof PendingCommand) {
            $commandExecutionResult->assertExitCode(0);
        }

        Notification::assertSentTimes(DailyCurrencyExchangeEmail::class, 3);
    }
}
