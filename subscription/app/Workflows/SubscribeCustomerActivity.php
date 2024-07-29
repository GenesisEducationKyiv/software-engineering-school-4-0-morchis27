<?php

namespace App\Workflows;

use App\DTO\CreationDTO\Subscriber\SubscriberDTO;
use App\Service\Subscription\SubscriptionServiceInterface;
use Workflow\Activity;
use Workflow\Models\StoredWorkflow;

class SubscribeCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    private SubscriptionServiceInterface $subscriptionService;
    public function __construct(int $index, string $now, StoredWorkflow $storedWorkflow, ...$arguments)
    {
        parent::__construct($index, $now, $storedWorkflow, $arguments);
        $this->subscriptionService = app(SubscriptionServiceInterface::class);
    }

    public function execute(string $email)
    {
        $subscriberDTO = new SubscriberDTO();
        $subscriberDTO->setEmail($email);

        $this->subscriptionService->subscribe($subscriberDTO);
    }
}
