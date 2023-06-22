<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands;

use Illuminate\Console\Command;
use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;

class EnvDatabaseConfigMigrateCommand extends Command
{
    use DatabaseConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:migrate
                            {database : The type of database}
                            {name : The name of the new database connection}
                            {postfix : The postfix for the database configuration}
                            {--P|--path= : The path where the database migration files are kept}
                            {--seed : Running seeders}
                            {--seeder= : Running a single seeder class}
                            {--refresh : Refreshing all migration}
                            {--rollback : Reverting migrations}
                            {--fresh : Re-run all migrations afresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate databases from env';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = config('dynamicdatabaseconfig.default_path');
        $database = $this->argument('database');
        $name = $this->argument('name');
        $postFix = $this->argument('postfix');

        if ($this->option('path')) {
            $path = $this->option('path');
        }

        $this->components->info("Running migrations for {$name} and path={$path}");

        $newConfig = $this->setNewEnvConfig($database, $postFix);
        $this->addNewConfig($database, $name, $newConfig);
        $this->createDatabase($database, $newConfig['database']);
        $this->execMigrationCommands($name, $path);
    }

    private function execMigrationCommands(string $name, null|string $path = '')
    {
        if ($this->option('rollback')) {
            $this->call('migrate:rollback', ['--database' => $name, '--path' => $path]);
            return;
        }

        if ($this->option('refresh')) {
            $this->call('migrate:refresh', ['--database' => $name, '--path' => $path]);
        } elseif ($this->option('fresh')) {
            $this->call('migrate:fresh', ['--database' => $name, '--path' => $path]);
        } else {
            $this->call('migrate', ['--database' => $name, '--path' => $path]);
        }

        if ($this->option('seed')) {
            $this->call('db:seed');
            return;
        }

        if ($seeder = $this->option('seeder')) {
            $this->call('db:seed', ['--class' => $seeder]);
        }
    }
}
