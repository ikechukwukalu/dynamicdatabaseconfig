<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;
use Ikechukwukalu\Dynamicdatabaseconfig\Tests\Seeders\DatabaseConfigurationSeeder;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fires_dynamic_database_config_commands(): void
    {

        // $this->artisan('database:env-migrate test_bench test_bench_one ONE --path=database/migrations')->assertSuccessful();

        $this->seed(DatabaseConfigurationSeeder::class);

        $this->artisan('dynamic:migrate one --path=database/migrations')->assertSuccessful();

        $this->artisan('dynamic:migrate one --seed --path=database/migrations')->assertSuccessful();

        $this->artisan('dynamic:migrate one --refresh --path=database/migrations')->assertSuccessful();

        $this->artisan('dynamic:migrate one --fresh --path=database/migrations')->assertSuccessful();

        $this->artisan('dynamic:seed one --seed')->assertSuccessful();

        $this->artisan('vendor:publish --tag=ddc-config')->assertSuccessful();

        $this->artisan('vendor:publish --tag=ddc-migrations')->assertSuccessful();
    }
}
