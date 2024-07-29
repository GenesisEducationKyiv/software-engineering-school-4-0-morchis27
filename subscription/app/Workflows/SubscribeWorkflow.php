<?php

namespace App\Workflows;

use Generator;
use Throwable;
use Workflow\ActivityStub;
use Workflow\Workflow;

class SubscribeWorkflow extends Workflow
{
    public $connection = 'shared';
    public $queue = 'workflow';

    public function execute()
    {
    }
}
