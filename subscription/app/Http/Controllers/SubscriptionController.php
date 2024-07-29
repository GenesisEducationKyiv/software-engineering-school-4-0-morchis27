<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotSavedException;
use App\Http\Requests\DeleteSubscriberRequest;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use App\Service\Subscription\SubscriptionServiceInterface;
use App\Workflows\SubscribeWorkflow;
use Illuminate\Http\JsonResponse;
use Workflow\WorkflowStub;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionServiceInterface $subscriptionService
    ) {
    }

    /**
     * @param StoreSubscriberRequest $request
     * @return JsonResponse
     */
    public function subscribe(StoreSubscriberRequest $request): JsonResponse
    {
        $createSubscriberDto = $this->subscriptionService->makeSubscriberDTO($request);

        $saga = WorkflowStub::make(SubscribeWorkflow::class);
        $saga->start($createSubscriberDto->getEmail());

        return $this->successResponse(null, 200);
    }

    public function unsubscribe(DeleteSubscriberRequest $request): JsonResponse
    {
        $createSubscriberDto = $this->subscriptionService->makeSubscriberDTO($request);
        $result = $this->subscriptionService->unsubscribe($createSubscriberDto);

        return $this->successResponse($result, 200);
    }

    /**
     * @param Subscriber $subscriber
     * @return JsonResponse
     */
    public function verify(Subscriber $subscriber): JsonResponse
    {
        return $this->successResponse($this->subscriptionService->verify($subscriber)->toArray());
    }
}
