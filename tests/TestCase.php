<?php

namespace Zerp\Lead\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zerp\Lead\Providers\LeadServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LeadServiceProvider::class];
    }
}
