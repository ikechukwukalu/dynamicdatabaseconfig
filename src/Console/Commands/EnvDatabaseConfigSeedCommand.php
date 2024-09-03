<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands;

use Illuminate\Console\Command;
use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;

class EnvDatabaseConfigSeedCommand extends Command
{
    use DatabaseConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:seed
                            {database : The type of database}
                            {name : The name of the new database connection}
                            {postfix : The postfix for the database configuration}
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
        $database = $this->argument('database');
        $name = $this->argument('name');
        $postFix = $this->argument('postfix');

        $this->components->info("Running seeder(s) for {$name}");

        $newConfig = $this->setNewEnvConfig($database, $postFix);
        $this->addNewConfig($database, $name, $newConfig);
        $this->createDatabase($database, $newConfig['database']);
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
