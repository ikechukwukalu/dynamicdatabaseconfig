<?php

return [
    /**
     * boolean - If all database fields should be encrypted
     */
    'hash' => env('DB_CONFIGURATIONS_HASH', true),
    /**
     * string - session key to set the $ref for dynamic database config
     */
    'session_ref' => env('SESSION_REF_KEY', '_db_ref'),
    /**
     * string - session key to set the $postFix for env database config
     * The $postFix value can be assigned to the session
     */
    'session_postfix' => '_db_session',
    /**
     * string - migration directory
     */
    'default_path' => 'database/migrations',
    /**
     * string - the request connection name
     */
    'connection_name' => '_db_connection'
];
