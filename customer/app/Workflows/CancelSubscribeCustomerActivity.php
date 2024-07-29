<?php

namespace App\Workflows;

use Workflow\Activity;

class CancelSubscribeCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';
    public function execute()
    {
        //
    }
}
