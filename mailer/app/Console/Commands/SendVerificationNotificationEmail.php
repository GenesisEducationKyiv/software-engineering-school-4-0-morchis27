<?php

namespace App\Console\Commands;

use App\Notifications\DailyCurrencyExchangeEmail;
use App\Notifications\VerifyEmailQueued;
use App\Services\CurrencyExchange\CurrencyServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;

class SendVerificationNotificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-verification-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $consumer = Kafka::consumer()
                ->subscribe('mail-requests')
                ->enableBatching()
                ->withBatchSizeLimit(500)
                ->withBatchReleaseInterval(1500)
                ->withHandler(function (
                    Collection $collection,
                    MessageConsumer $consumer
                ) {
                    /** @var ConsumerMessage $message */
                    foreach ($collection as $message) {
                        Notification::route(
                            'mail',
                            $message->getBody()['data']['to']
                        )
                            ->notify(new VerifyEmailQueued());
                    }
                })
                ->build();

            $consumer->consume();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
