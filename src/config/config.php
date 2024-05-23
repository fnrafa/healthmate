<?php

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeders'
    ],
    'environments' => [
        'default_database' => 'mysql',
        'mysql' => [
            'adapter' => $_ENV['DB_CONNECTION'],
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASSWORD'],
            'port' => $_ENV['DB_PORT'],
            'charset' => 'utf8',
        ]
    ]
];
