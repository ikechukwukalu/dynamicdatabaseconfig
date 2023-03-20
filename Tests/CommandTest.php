<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fires_dynamic_mail_config_commands(): void
    {
        $this->artisan('vendor:publish --tag=ddc-config')->assertSuccessful();

        $this->artisan('vendor:publish --tag=ddc-migrations')->assertSuccessful();
    }
}
