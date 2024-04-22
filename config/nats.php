<?php

return [
    // The NATS server host address
    'host' => env('NATS_HOST', 'localhost'),

    // JWT for NATS authentication (if required)
    'jwt' => env('NATS_JWT', null),

    // The client language (library used)
    'lang' => 'php',

    // Password for NATS authentication (if required)
    'pass' => env('NATS_PASS', null),

    // Toggle strict protocol adherence
    'pedantic' => env('NATS_PEDANTIC', false),

    // The NATS server port
    'port' => env('NATS_PORT', 4222),

    // Enable auto-reconnect feature
    'reconnect' => env('NATS_RECONNECT', true),

    // Timeout for NATS operations in seconds
    'timeout' => env('NATS_TIMEOUT', 1),

    // Token for NATS authentication (if required)
    'token' => env('NATS_TOKEN', null),

    // Username for NATS authentication (if required)
    'user' => env('NATS_USER', null),

    // NKey for NATS authentication (if required)
    'nkey' => env('NATS_NKEY', null),

    // Enable verbose logging for debugging purposes
    'verbose' => env('NATS_VERBOSE', false),

    // Version of the client, typically the package version
    'version' => env('NATS_VERSION', 'dev'),
];

