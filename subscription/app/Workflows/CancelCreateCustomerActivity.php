<?php

namespace App\Workflows;

use Workflow\Activity;

class CancelCreateCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    public function execute()
    {
    }
}
