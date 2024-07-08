<?php

namespace Tests\Unit\Repository;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\CreationDTO\Subscriber\UpdateSubscriberDTO;
use App\Models\Subscriber;
use App\Repository\Subscriber\SubscriberRepository;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class SubscriberRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SubscriberRepositoryInterface $repository;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        parent::setUp();
        // @phpstan-ignore-next-line
        $this->repository = new SubscriberRepository($this->app->make(Subscriber::class));
    }

    public function testFindByIdReturnsSubscriber(): void
    {
        $subscriber = Subscriber::factory()->create();
        // @phpstan-ignore-next-line
        $result = $this->repository->findById($subscriber->id);

        // @phpstan-ignore-next-line
        $this->assertEquals($subscriber->id, $result->id);
        $this->assertInstanceOf(Subscriber::class, $result);
    }

    public function testAllReturnsAllSubscribers(): void
    {
        Subscriber::factory()->count(3)->create();
        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(Subscriber::class, $result->firstOrFail());

        $this->assertCount(3, $result);
    }

    public function testCreateCreatesNewSubscriber(): void
    {
        $email = Faker::create()->safeEmail;

        $request = new Request();
        $request->setMethod('POST');
        $request->request->add(['email' => $email]);

        $createSubscriberDTO = new CreateSubscriberDTO();
        $createSubscriberDTO->fillByRequest($request);

        $subscriber = $this->repository->create($createSubscriberDTO);
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals($email, $subscriber->email);
    }

    public function testUpdateUpdatesSubscriber(): void
    {
        $subscriber = Subscriber::factory()->create(['email' => Faker::create()->safeEmail]);
        // @phpstan-ignore-next-line
        $updateSubscriberDTO = new UpdateSubscriberDTO($subscriber->id);
        $newEmail = Faker::create()->safeEmail;
        $updateSubscriberDTO->setEmail($newEmail);

        $updatedSubscriber = $this->repository->update($updateSubscriberDTO);

        $this->assertInstanceOf(Subscriber::class, $updatedSubscriber);
        $this->assertEquals($newEmail, $updatedSubscriber->email);
    }

    public function testDeleteRemovesSubscriber(): void
    {
        $subscriber = Subscriber::factory()->create();

        // @phpstan-ignore-next-line
        $deleted = $this->repository->delete($subscriber->id);

        $this->assertTrue($deleted);
        // @phpstan-ignore-next-line
        $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
    }

    public function testVerifyMarksEmailAsVerified(): void
    {
        $subscriber = Subscriber::factory()->create(['email_verified_at' => null]);

        // @phpstan-ignore-next-line
        $this->repository->verify($subscriber);

        $subscriber = $subscriber->fresh();

        $this->assertNotNull($subscriber);
        // @phpstan-ignore-next-line
        $this->assertNotNull($subscriber->email_verified_at);
    }

    public function testIsVerifiedChecksVerificationStatus(): void
    {
        $subscriber = Subscriber::factory()->create(['email_verified_at' => now()]);

        // @phpstan-ignore-next-line
        $verified = $this->repository->isVerified($subscriber);

        $this->assertTrue($verified);
    }
}
