<?php

namespace Repository;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\CreationDTO\Subscriber\UpdateSubscriberDTO;
use App\Models\Subscriber;
use App\Repositories\Subscriber\SubscriberRepository;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
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
        $this->repository = new SubscriberRepository($this->app->make(Subscriber::class));
    }

    public function testFindByIdReturnsSubscriber(): void
    {
        $subscriber = Subscriber::factory()->create();

        $result = $this->repository->findById($subscriber->id);

        $this->assertEquals($subscriber->id, $result->id);
        $this->assertInstanceOf(Subscriber::class, $result);
    }

    public function testAllReturnsAllSubscribers()
    {
        Subscriber::factory()->count(3)->create();
        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(Subscriber::class, $result->firstOrFail());

        $this->assertCount(3, $result);
    }

    public function testCreateCreatesNewSubscriber()
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

    public function testUpdateUpdatesSubscriber()
    {
        $subscriber = Subscriber::factory()->create(['email' => Faker::create()->safeEmail]);
        $updateSubscriberDTO = new UpdateSubscriberDTO($subscriber->id);
        $newEmail = Faker::create()->safeEmail;
        $updateSubscriberDTO->setEmail($newEmail);

        $updatedSubscriber = $this->repository->update($updateSubscriberDTO);

        $this->assertInstanceOf(Subscriber::class, $updatedSubscriber);
        $this->assertEquals($newEmail, $updatedSubscriber->email);
    }

    public function testDeleteRemovesSubscriber()
    {
        $subscriber = Subscriber::factory()->create();

        $deleted = $this->repository->delete($subscriber->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
    }

    public function testVerifyMarksEmailAsVerified()
    {
        $subscriber = Subscriber::factory()->create(['email_verified_at' => null]);

        $this->repository->verify($subscriber);
        $this->assertNotNull($subscriber->fresh()->email_verified_at);
    }

    public function testIsVerifiedChecksVerificationStatus()
    {
        $subscriber = Subscriber::factory()->create(['email_verified_at' => now()]);

        $verified = $this->repository->isVerified($subscriber);

        $this->assertTrue($verified);
    }
}
