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
                            {--P|--path= : The path where the database migration files are kept}';

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
        $this->call('migrate', ['--database' => $name, '--path' => $path]);
    }
}
