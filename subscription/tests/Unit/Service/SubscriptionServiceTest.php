<?php

namespace Tests\Unit\Service;

use App\DTO\CreationDTO\Subscriber\SubscriberDTO;
use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Events\Subscribed;
use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\NotifiableInterface;
use App\Models\Subscriber;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use App\Service\Message\Messages\EmailVerificationMessage;
use App\Service\MessageBroker\MessageBrokerInterface;
use App\Service\Subscription\SubscriptionService;
use App\Service\Subscription\SubscriptionServiceInterface;
use App\Utils\Utilities;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\MockObject\Exception;
use Faker\Factory as Faker;
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
     * @var MockObject|Utilities
     */
    private Utilities|MockObject $utilities;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subscriberRepository = $this->createMock(SubscriberRepositoryInterface::class);
        $this->utilities = $this->createMock(Utilities::class);
        $this->messageBroker = $this->createMock(MessageBrokerInterface::class);

        $this->subscriptionService = new SubscriptionService(
            $this->subscriberRepository,
            $this->messageBroker,
            $this->utilities
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
