<?php

namespace Service;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Events\Subscribed;
use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\NotifiableInterface;
use App\Models\Subscriber;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
use App\Service\Subscription\SubscriptionService;
use App\Service\Subscription\SubscriptionServiceInterface;
use App\Utils\Utilities;
use Illuminate\Support\Facades\Cache;
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
        $this->subscriptionService = new SubscriptionService($this->subscriberRepository, $this->utilities);
    }

    /**
     * @throws Exception
     * @throws ModelNotSavedException
     */
    public function testSubscribeReturnsNoException(): void
    {
        Event::fake();
        $createSubscriberDTO = $this->createMock(CreateSubscriberDTO::class);

        // @phpstan-ignore-next-line
        $this->subscriberRepository->expects($this->once())
            ->method('create')
            ->with($createSubscriberDTO)
            ->willReturn(new Subscriber());

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

        $creationSubscriberDTO = $this->subscriptionService->makeCreationDTO($request);

        $this->assertInstanceOf(CreateSubscriberDTO::class, $creationSubscriberDTO);
    }

    /**
     * @throws Exception
     */
    public function testVerifyReturnsBasicVerificationDTO(): void
    {
        $subscriber = $this->createMock(Subscriber::class);
        // @phpstan-ignore-next-line
        $this->subscriberRepository->expects($this->once())
            ->method('isVerified')
            ->with($subscriber);

        $result = $this->subscriptionService->verify($subscriber);

        $this->assertInstanceOf(BasicVerificationDTOInterface::class, $result);
    }

    /**
     * @throws Exception
     */
    public function testHandleVerificationNotificationReturnsNoException(): void
    {
        $subscriber = $this->createMock(NotifiableInterface::class);
        $subscriber->expects($this->once())
            ->method('notify');

        $this->subscriptionService->handleVerificationNotification($subscriber);
    }

    /**
     * @throws Exception
     */
    public function testSendDailyExchangeRateNewsLetterNotification(): void
    {
        $subscriber = $this->createMock(NotifiableInterface::class);
        $exchangeRateDTO = $this->createMock(ExchangeRateDTO::class);

        $exchangeRateDTO->expects($this->once())
            ->method('getSellExchangeRate');
        $subscriber->expects($this->once())
            ->method('notify');

        $this->subscriptionService->sendDailyExchangeRateNewsLetterNotification($subscriber, $exchangeRateDTO);
    }
}
