<?php

function getDatabaseConfig(): array
{
    return [
        'database' => [
            'prod' => [
                'url' => 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME_PROD'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PWD']
            ],
            'test' => [
                'url' => 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME_PROD'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PWD']
            ]
        ]
    ];
}
