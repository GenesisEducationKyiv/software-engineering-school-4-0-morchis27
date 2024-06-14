<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\StoreSubscriberRequest;
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
        $createSubscriberDto = $this->subscriptionService->makeCreationDTO($request);
        $this->subscriptionService->subscribe($createSubscriberDto);

        return $this->successResponse(null, 200);
    }

    /**
     * @return JsonResponse
     */
    public function verify(): JsonResponse
    {
        return $this->successResponse($this->subscriptionService->verify()->toArray());
    }
}
