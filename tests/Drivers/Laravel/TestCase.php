<?php

declare(strict_types=1);

namespace Tests\Drivers\Laravel;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    final public function createApplication()
    {
        $app = require __DIR__.'/../../../drivers/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
