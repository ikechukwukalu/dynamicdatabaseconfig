<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Trait;

use Config;
use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait DatabaseConfig
{
    /**
     * Add new database connection
     *
     * @param string $database
     * @param string $name
     * @param array $newConfig
     * @return void
     */
    public function addNewConfig(string $database, string $name, array $newConfig): void
    {
        if (!in_array($database, $this->listOfDatabases())) {
            return;
        }

        $this->newConfig($database, $name, $newConfig);
    }

    /**
     * Create database
     *
     * @param string $name
     * @param string $schemaName
     * @return void
     */
    public function createDatabase(string $name, string $schemaName): void
    {
        DB::connection($name)->statement("CREATE DATABASE IF NOT EXISTS {$schemaName};");
    }

    /**
     * Migrate database
     *
     * @param string $name
     * @param null|string $path
     * @return void
     */
    public function migrateDatabase(string $name, null|string $path): void
    {
        if (!$path) {
            Artisan::call('migrate', ['--database' => $name]);

            return;
        }

        Artisan::call('migrate', ['--database' => $name, '--path' => $path]);
    }

    /**
     * Get database config template
     *
     * @param string $database
     * @return null|array
     */
    public function getDatabaseConfigTemplate(string $database): null|array
    {
        if (!in_array($database, $this->listOfDatabases())) {
            return null;
        }

        return Config::get('database.connections.' . $database);
    }

    /**
     * Get database config
     *
     * @return array
     */
    public function getDatabaseConfig(): array
    {
        return Config::get('database');
    }

    /**
     * Get dynamic database configuration
     *
     * @param string $ref
     * @return array
     */
    public function getDynamicDatabaseConfiguration(string $ref): array
    {
        $databaseConfig = DatabaseConfiguration::configuredDatabase($ref)
                            ->first();

        return [
            $databaseConfig->database ?? null,
            $databaseConfig->configuration ?? null,
            $databaseConfig->name ?? null
        ];
    }

    /**
     * Set and retrieve new dynamic database configuration
     *
     * @param string $database
     * @param array $configuration
     * @return array
     */
    public function setNewDynamicConfig(string $database, array $configuration): array
    {
        return array_merge($this->getDatabaseConfigTemplate($database),
                $configuration);
    }

    /**
     * Set and retrieve new env config
     *
     * @param string $database
     * @param string $postFix
     * @return array
     */
    public function setNewEnvConfig(string $database, string $postFix): array
    {
        return array_merge($this->getDatabaseConfigTemplate($database), [
            'host' =>  env('DB_HOST_' . $postFix),
            'port' => env('DB_PORT_' . $postFix),
            'database' => env('DB_DATABASE_' . $postFix),
            'username' => env('DB_USERNAME_' . $postFix),
            'password' => env('DB_PASSWORD_' . $postFix)
        ]);
    }

    /**
     * Set database config
     *
     * @param array $config
     * @return void
     */
    private function setDatabaseConfig(array $config): void
    {
        Config::set('database', $config);
    }

    /**
     * Merge new database config to existing config
     *
     * @param string $database
     * @param array $newConfig
     * @return array
     */
    private function mergeToDatabaseConfig(string $database, array $newConfig): array
    {
        return array_merge(Config::get('database.connections.' . $database),
                        $newConfig);
    }

    /**
     * New config
     *
     * @param string $database
     * @param string $name
     * @param array $newConfig
     * @return void
     */
    private function newConfig(string $database, string $name, array $newConfig): void
    {
        $config = $this->getDatabaseConfig();
        $newDatabaseConfig = $this->mergeToDatabaseConfig($database,
                            $newConfig);
        $config['connections'][$name] = $newDatabaseConfig;
        $this->setDatabaseConfig($config);
    }

    /**
     * List of databases
     *
     * @return array
     */
    private function listOfDatabases(): array
    {
        return array_keys(Config::get('database.connections'));
    }
}
