<?php

namespace Ikechukwukalu\Dynamicmailconfig\Tests;

use Ikechukwukalu\Dynamicmailconfig\DynamicMailConfigServiceProvider;
use Ikechukwukalu\Dynamicmailconfig\Middleware\DynamicMailConfig;

class ServiceProviderTest extends TestCase
{
    public function test_merges_config(): void
    {
        static::assertSame(
            $this->app->make('files')
                ->getRequire(DynamicMailConfigServiceProvider::CONFIG),
            $this->app->make('config')->get('dynamicmailconfig')
        );
    }

    public function test_publishes_middleware(): void
    {
        $middleware = $this->app->make('router')->getMiddleware();

        static::assertSame(DynamicMailConfig::class, $middleware['dynamic.mail.config']);
        static::assertArrayHasKey('dynamic.mail.config', $middleware);
    }

}
