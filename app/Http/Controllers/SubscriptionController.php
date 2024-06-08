<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyExistsException;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use App\Service\Subscription\SubscriptionService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    )
    {
    }

    /**
     * @throws AlreadyExistsException
     * @throws Exception
     */
    public function subscribe(StoreSubscriberRequest $request): JsonResponse
    {
        $email = $request->get('email');
        $subscriber = Subscriber::where('email', $email)->first();
        if($subscriber) {
            throw new AlreadyExistsException();
        }

        $this->subscriptionService->subscribe($email);

        return $this->successResponse(null, 200);
    }

    public function verify(Subscriber $subscriber, Request $request): View
    {
        if (!$request->hasValidSignature()) {
            return view('auth/email-error-verified');
        }
        if ($subscriber->hasVerifiedEmail()) {
            return view('auth/email-already-verified');
        }

        $subscriber->markEmailAsVerified();

        return view('auth/email-verified');
    }
}
