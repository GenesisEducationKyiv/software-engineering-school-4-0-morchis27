<?php

namespace App\Workflows;

use Workflow\Activity;

class SubscribeCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    public function execute()
    {
    }
}
