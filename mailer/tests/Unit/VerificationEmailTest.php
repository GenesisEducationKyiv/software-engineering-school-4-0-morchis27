<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;
use App\Notifications\VerifyEmailQueued;

class VerificationEmailTest extends TestCase
{
    public function test_verification_email_sent()
    {
        Notification::fake();
        Kafka::fake();

        Kafka::shouldReceiveMessages([
            new ConsumedMessage(
                topicName: 'mail-requests',
                partition: 0,
                headers: [],
                body: ['data' => ['to' => 'test@example.com']],
                key: null,
                offset: 0,
                timestamp: 0
            ),
        ]);

        $this->artisan('app:send-verification-email')
            ->assertExitCode(0);

        Notification::assertSentTimes(VerifyEmailQueued::class, 1);
    }
}
