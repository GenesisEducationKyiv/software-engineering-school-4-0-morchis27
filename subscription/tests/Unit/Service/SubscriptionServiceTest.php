<?php

namespace Tests\Unit\Service;

use App\DTO\CreationDTO\Subscriber\SubscriberDTO;
use App\Events\Subscribed;
use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use App\Service\MessageBroker\MessageBrokerInterface;
use App\Service\Subscription\SubscriptionService;
use App\Service\Subscription\SubscriptionServiceInterface;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    /**
     * @var MockObject|SubscriberRepositoryInterface
     */
    private SubscriberRepositoryInterface|MockObject $subscriberRepository;
    private SubscriptionServiceInterface $subscriptionService;
    private MessageBrokerInterface $messageBroker;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subscriberRepository = $this->createMock(SubscriberRepositoryInterface::class);
        $this->messageBroker = $this->createMock(MessageBrokerInterface::class);

        $this->subscriptionService = new SubscriptionService(
            $this->subscriberRepository,
            $this->messageBroker
        );
    }

    /**
     * @throws Exception
     * @throws ModelNotSavedException
     */
    public function testSubscribeReturnsNoException(): void
    {
        Event::fake();
        $createSubscriberDTO = $this->createMock(SubscriberDTO::class);
        $messageBroker = $this->createMock(MessageBrokerInterface::class);

        // @phpstan-ignore-next-line
        $this->subscriberRepository->expects($this->once())
            ->method('create')
            ->with($createSubscriberDTO)
            ->willReturn(new Subscriber());

        $messageBroker->expects($this->once())
            ->method('publish');

        $this->subscriptionService->subscribe($createSubscriberDTO);

        Event::assertDispatched(Subscribed::class);
    }

    /**
     * @throws Exception
     */
    public function testMakeCreationDTOReturnsCreationDTO(): void
    {
        $request = $this->createMock(StoreSubscriberRequest::class);
        $request->expects($this->once())
            ->method('string')
            ->willReturn(str(Faker::create()->safeEmail));

        $creationSubscriberDTO = $this->subscriptionService->makeSubscriberDTO($request);

        $this->assertInstanceOf(SubscriberDTO::class, $creationSubscriberDTO);
    }
}
