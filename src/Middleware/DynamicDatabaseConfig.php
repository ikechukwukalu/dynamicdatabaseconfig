<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Middleware;

use Closure;
use Ikechukwukalu\Dynamicdatabaseconfig\Trait\DatabaseConfig;
use Illuminate\Http\Request;

class DynamicDatabaseConfig
{
    use DatabaseConfig;

    public function handle(Request $request, Closure $next, null|string $ref = null)
    {
        if (!$ref) {
            $ref = session(config('dynamicdatabaseconfig.session_ref', '_db_ref'));
        }

        [$database, $configuration, $name] = $this->getDynamicDatabaseConfiguration($ref);

        $request->merge(['_db_connection' => $name]);

        if ($database) {
            $newConfig = $this->setNewDynamicConfig($database, $configuration);
            $this->addNewConfig($database, $name, $newConfig);
            $this->createDatabase($database, $configuration['database']);
        }

        return $next($request);
    }

}
