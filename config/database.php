<?php

function getDatabaseConfig(): array
{
    return [
        'database' => [
            'prod' => [
                'url' => 'mysql:host=localhost:3306;dbname=absen_api',
                'username' => 'root',
                'password' => ''
            ],
            'test' => [
                'url' => 'mysql:host=localhost:3306;dbname=absen_api_test',
                'username' => 'root',
                'password' => ''
            ]
        ]
    ];
}
