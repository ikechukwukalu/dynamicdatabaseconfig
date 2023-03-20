<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;

class EnvDatabaseConfig
{
    use DatabaseConfig;

    public function handle(Request $request, Closure $next, string $database, string $name, null|string $postFix = null, null|string $path = null)
    {
        if (!$postFix) {
            [$postFix, $path] = session(config('dynamicdatabaseconfig.session_variable'));
        }

        $newConfig = $this->setNewEnvConfig($database, $postFix);
        $this->addNewConfig($database, $name, $newConfig);
        $this->createDatabase($database, $newConfig['database']);

        return $next($request);
    }

}
