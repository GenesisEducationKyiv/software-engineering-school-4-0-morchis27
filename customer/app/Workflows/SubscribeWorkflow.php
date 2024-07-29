<?php

namespace App\Workflows;

use Generator;
use Illuminate\Support\Facades\Log;
use Throwable;
use Workflow\ActivityStub;
use Workflow\Workflow;

class SubscribeWorkflow extends Workflow
{
    public $connection = 'shared';
    public $queue = 'workflow';

    /**
     * @throws Throwable
     */
    public function execute(string $email): Generator
    {
        try {
            Log::info('asdasd1112312');
            yield ActivityStub::make(CreateCustomerActivity::class, $email);
            $this->addCompensation(fn() => ActivityStub::make(CancelCreateCustomerActivity::class, $email));

            yield ActivityStub::make(SubscribeCustomerActivity::class, $email);
            $this->addCompensation(fn() => ActivityStub::make(CancelSubscribeCustomerActivity::class, $email));
        } catch (Throwable $th) {
            yield from $this->compensate();
            throw $th;
        }
    }
}
