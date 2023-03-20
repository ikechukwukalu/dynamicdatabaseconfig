<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests;

use Ikechukwukalu\Dynamicdatabaseconfig\DynamicDatabaseConfigServiceProvider;
use Ikechukwukalu\Dynamicdatabaseconfig\Middleware\DynamicDatabaseConfig;

class ServiceProviderTest extends TestCase
{
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
    }

}
