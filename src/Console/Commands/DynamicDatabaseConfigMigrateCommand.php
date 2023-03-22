<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands;

use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;
use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;
use Illuminate\Console\Command;

class DynamicDatabaseConfigMigrateCommand extends Command
{
    use DatabaseConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic:migrate
                            {ref : The ref for the database configuration}
                            {--P|--path= : The path where the database migration files are stored}
                            {--seeder= : Running a single seeder class}
                            {--refresh : Refreshing all migration}
                            {--rollback : Reverting migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate databases dynamically';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = config('dynamicdatabaseconfig.default_path');
        $ref = $this->argument('ref');

        if ($this->option('path')) {
            $path = $this->option('path');
        }

        [$database, $configuration, $name] = $this->getDynamicDatabaseConfiguration($ref);

        if (!$database) {
            $this->components->error("No database configuration was found");
            return;
        }

        $this->components->info("Running migrations for {$name} and path={$path} with ref={$ref}");

        $newConfig = $this->setNewDynamicConfig($database, $configuration);
        $this->addNewConfig($database, $name, $newConfig);
        $this->createDatabase($database, $configuration['database']);

        if ($this->option('refresh')) {
            $this->call('migrate:refresh', ['--database' => $name, '--path' => $path]);
        } else {
            $this->call('migrate', ['--database' => $name, '--path' => $path]);
        }

        if ($this->option('rollback')) {
            $this->call('migrate:rollback', ['--database' => $name, '--path' => $path]);
            return;
        }

        if ($seeder = $this->option('seeder')) {
            $this->call('db:seed', ['--class' => $seeder]);
        }
    }
}
