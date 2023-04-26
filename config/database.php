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

// 'url' => 'mysql:host=localhost;dbname=u1792164_byod',
//                 'username' => 'u1792164_byod',
//                 'password' => 'u1792164_byod'