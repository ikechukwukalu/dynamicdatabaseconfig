<?php

namespace Ikechukwukalu\Dynamicmailconfig\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fires_dynamic_mail_config_commands(): void
    {
        $this->artisan('vendor:publish --tag=dmc-config')->assertSuccessful();

        $this->artisan('vendor:publish --tag=dmc-migrations')->assertSuccessful();
    }
}
