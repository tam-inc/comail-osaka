<?php

$dbInfo = parse_url(env('CLEARDB_DATABASE_URL', env('DB_URL')));

return [
    'fetch' => PDO::FETCH_OBJ,
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => $dbInfo['host'],
            'port' => $dbInfo['port'],
            'database' => substr($dbInfo['path'], 1),
            'username' => $dbInfo['user'],
            'password' => $dbInfo['pass'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'testing' => [
            'driver'    => 'sqlite',
            'database'  => ':memory:',
            'prefix'    => '',
            'options'   => [
                PDO::ATTR_PERSISTENT => true,
            ],
        ],
    ],

    'migrations' => 'migrations',
];
