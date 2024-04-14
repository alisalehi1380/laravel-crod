<?php

namespace Milwad\LaravelCrod\Tests;

use Milwad\LaravelCrod\LaravelCrodServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, string>
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelCrodServiceProvider::class,
        ];
    }
}
