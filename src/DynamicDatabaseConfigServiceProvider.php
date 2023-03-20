<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig;


use Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands\DynamicDatabaseConfigMigrateCommand;
use Ikechukwukalu\Dynamicdatabaseconfig\Console\Commands\EnvDatabaseConfigMigrateCommand;
use Ikechukwukalu\Dynamicdatabaseconfig\Middleware\DynamicDatabaseConfig;
use Ikechukwukalu\Dynamicdatabaseconfig\Middleware\EnvDatabaseConfig;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class DynamicDatabaseConfigServiceProvider extends ServiceProvider
{
    public const DB = __DIR__.'/migrations';
    public const CONFIG = __DIR__.'/config/dynamicdatabaseconfig.php';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DynamicDatabaseConfigMigrateCommand::class,
                EnvDatabaseConfigMigrateCommand::class
            ]);
        }

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('dynamic.database.config', DynamicDatabaseConfig::class);
        $router->aliasMiddleware('env.database.config', EnvDatabaseConfig::class);

       $this->loadMigrationsFrom(self::DB);

        $this->publishes([
            self::CONFIG => config_path('dynamicdatabaseconfig.php'),
        ], 'ddc-config');

        $this->publishes([
            self::DB => database_path('migrations'),
        ], 'ddc-migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG, 'dynamicdatabaseconfig'
        );
    }
}
