<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests;

use Ikechukwukalu\Dynamicdatabaseconfig\DynamicDatabaseConfigServiceProvider;
use Ikechukwukalu\Dynamicdatabaseconfig\Middleware\DynamicDatabaseConfig;
use Ikechukwukalu\Dynamicdatabaseconfig\Middleware\EnvDatabaseConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_merges_config(): void
    {
        static::assertSame(
            $this->app->make('files')
                ->getRequire(DynamicDatabaseConfigServiceProvider::CONFIG),
            $this->app->make('config')->get('dynamicdatabaseconfig')
        );
    }

    public function test_publishes_middleware(): void
    {
        $middleware = $this->app->make('router')->getMiddleware();

        static::assertSame(DynamicDatabaseConfig::class, $middleware['dynamic.database.config']);
        static::assertArrayHasKey('dynamic.database.config', $middleware);

        static::assertSame(EnvDatabaseConfig::class, $middleware['env.database.config']);
        static::assertArrayHasKey('env.database.config', $middleware);
    }

}
