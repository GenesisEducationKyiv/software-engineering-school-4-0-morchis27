<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\DeleteSubscriberRequest;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use App\Service\Subscription\SubscriptionServiceInterface;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionServiceInterface $subscriptionService
    ) {
    }

    /**
     * @param StoreSubscriberRequest $request
     * @return JsonResponse
     * @throws ModelNotSavedException
     */
    public function subscribe(StoreSubscriberRequest $request): JsonResponse
    {
        $createSubscriberDto = $this->subscriptionService->makeSubscriberDTO($request);
        $this->subscriptionService->subscribe($createSubscriberDto);

        return $this->successResponse(null, 200);
    }

    public function unsubscribe(DeleteSubscriberRequest $request): JsonResponse
    {
        $createSubscriberDto = $this->subscriptionService->makeSubscriberDTO($request);
        $result = $this->subscriptionService->unsubscribe($createSubscriberDto);

        return $this->successResponse($result, 200);
    }

    /**
     * @return JsonResponse
     */
    public function verify(Subscriber $subscriber): JsonResponse
    {
        return $this->successResponse($this->subscriptionService->verify($subscriber)->toArray());
    }
}
