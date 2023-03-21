<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Tests\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;

class DatabaseConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = \Config::get('database.connections.test_bench');
        $config['database'] = 'two_db';

        DatabaseConfiguration::firstOrCreate(
        ['ref' => 'two'],
        [
            'ref' => 'two',
            'name' => 'test_bench_two',
            'database' => 'test_bench',
            'configuration' => $config
        ]);
    }
}
