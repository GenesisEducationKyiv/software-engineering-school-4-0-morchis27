<?php

namespace App\Workflows;

use Workflow\Activity;

class CreateCustomerActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    public function execute(string $email)
    {
    }
}
