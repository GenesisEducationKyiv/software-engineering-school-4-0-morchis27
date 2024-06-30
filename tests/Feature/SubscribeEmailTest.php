<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Faker\Factory as Faker;

class SubscribeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testSubscriptionReturnsOkOnSuccess(): void
    {
        $faker = Faker::create();

        $email = $faker->safeEmail;

        $formData = [
            'email' => $email,
        ];

        $response = $this->call('POST', '/api/subscribe', $formData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
        ]);
        $subscriber = Subscriber::where('email', $email)->first();

        if (!config('app.shouldBeVerified')) {
            $this->assertNotNull($subscriber);
        }
    }

    public function testSubscriptionReturnsConflictOnIncorrectEmail(): void
    {
        $faker = Faker::create();

        $incorrectEmail = $faker->word;

        $formData = [
            'email' => $incorrectEmail,
        ];

        $response = $this->call('POST', '/api/subscribe', $formData);
        $response->assertStatus(400);
    }

    public function testSubscriptionReturnsConflictOnSubscribingExistingEmail(): void
    {
        $faker = Faker::create();

        $email = $faker->safeEmail;

        Subscriber::factory()->create([
            'email' => $email,
        ]);

        $formData = [
            'email' => $email,
        ];

        $response = $this->call('POST', '/api/subscribe', $formData);
        $response->assertStatus(409);
    }

    public function testEmailVerificationProcess(): void
    {
        Notification::fake();

        $subscriber = Subscriber::factory()->unverified()->create();

        $subscriber->sendEmailVerificationNotification();

        Notification::assertSentTo($subscriber, VerifyEmail::class);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['subscriber' => $subscriber->id, 'hash' => sha1($subscriber->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(200);

        $subscriber->refresh();
        $this->assertNotNull($subscriber->email_verified_at);
    }
}
