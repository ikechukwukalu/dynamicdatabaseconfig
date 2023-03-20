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
     * string - session key to set the $postFix and $path for env database config
     */
    'session_variable' => '_db_session',
    /**
     * string - value for session_variable
     *
     * index 0 must be set for the $postFix middleware variable
     * index 1 must be set for the $path middleware variable
     */
    'session_value' => ['ONE', 'database/migrations'],
    /**
     * string - migration directory
     */
    'default_path' => 'database/migrations'
];
