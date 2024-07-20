<?php

namespace Tests\Unit;

use App\Notifications\DailyCurrencyExchangeEmail;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;
use App\Notifications\VerifyEmailQueued;

class DailyExchangeRateEmailTest extends TestCase
{
    public function test_verification_email_sent()
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

        $this->artisan('app:send-email')
            ->assertExitCode(0);

        Notification::assertSentTimes(DailyCurrencyExchangeEmail::class, 3);
    }
}
