<?php

namespace App\Workflows;

use App\Service\Customer\CustomerServiceInterface;
use Workflow\Activity;
use Workflow\Models\StoredWorkflow;

class CreateCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    private CustomerServiceInterface $customerService;

    public function __construct(int $index, string $now, StoredWorkflow $storedWorkflow, ...$arguments)
    {
        parent::__construct($index, $now, $storedWorkflow, $arguments);
        $this->customerService = app(CustomerServiceInterface::class);
    }

    public function execute(string $email)
    {
        $this->customerService->create($email);
    }
}
