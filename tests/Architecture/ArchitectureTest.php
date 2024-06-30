<?php

namespace Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ArchitectureTest
{
    public function testRepositoryDoesNotDependOnOtherLayers(): Rule
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

    public function testServiceDoesNotDependOnUpperLayers(): Rule
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

    public function testModelDoesNotDependOnController(): Rule
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
