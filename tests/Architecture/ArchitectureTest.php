<?php

namespace Tests\Architecture;


use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ArchitectureTest
{
    public function test_repository_does_not_depend_on_other_layers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Repositories'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Service'),
                Selector::inNamespace('App\Controller'),
                Selector::inNamespace('App\Jobs'),
                Selector::inNamespace('App\Events'),
                Selector::inNamespace('App\Handlers'),
                Selector::inNamespace('App\Notifications'),
            )
            ->because('repository must not depend on layers that utilize repositories as source of data');
    }

    public function test_service_does_not_depend_on_upper_layers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Service'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Controller'),
                Selector::inNamespace('App\Jobs'),
            )
            ->because('service must not depend on any class that is utilizing service to perform some task');
    }

    public function test_model_does_not_depend_on_controller(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Models'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Controller'),
            )
            ->because('service must not depend on any class that is utilizing service to perform some task');
    }
}
