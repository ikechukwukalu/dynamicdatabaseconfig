<?php

namespace Ikechukwukalu\Dynamicmailconfig\Tests;

use Ikechukwukalu\Dynamicmailconfig\DynamicMailConfigServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../src/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [DynamicMailConfigServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('auth.guards.sanctum', [
                        'driver' => 'session',
                        'provider' => 'users',
                    ]);
    }
}
