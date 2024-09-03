<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands;

use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;
use Illuminate\Console\Command;

class DynamicDatabaseConfigSeedCommand extends Command
{
    use DatabaseConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic:seed
                            {ref : The ref for the database configuration}
                            {--seed : Running seeders}
                            {--seeder= : Running a single seeder class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding into databases from env';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $ref = $this->argument('ref');

        [$database, $configuration, $name] = $this->getDynamicDatabaseConfiguration($ref);

        if (!$database) {
            $this->components->error("No database configuration was found");
            return;
        }

        $this->components->info("Running seeder(s) for {$name} with ref={$ref}");

        $newConfig = $this->setNewDynamicConfig($database, $configuration);
        $this->addNewConfig($database, $name, $newConfig);
        $this->createDatabase($database, $configuration['database']);
        $this->execSeederCommands($name);
    }

    private function execSeederCommands(string $name)
    {
        if ($this->option('seed')) {
            $this->call('db:seed');
            return;
        }

        if ($seeder = $this->option('seeder')) {
            $this->call('db:seed', ['--class' => $seeder]);
        }
    }
}
