<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests;

use Ikechukwukalu\Dynamicdatabaseconfig\DynamicDatabaseConfigServiceProvider;
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
        return [DynamicDatabaseConfigServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('auth.guards.sanctum', [
                        'driver' => 'session',
                        'provider' => 'users',
                    ]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'test_bench');
        $app['config']->set('database.connections.test_bench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
