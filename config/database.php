<?php

function getDatabaseConfig(): array
{
    return [
        'database' => [
            'prod' => [
                'url' => 'mysql:host=db;dbname=absen_api',
                'username' => 'root',
                'password' => '4377'
            ],
            'test' => [
                'url' => 'mysql:host=db;dbname=absen_api_test',
                'username' => 'root',
                'password' => '4377'
            ]
        ]
    ];
}

// 'url' => 'mysql:host=localhost;dbname=u1792164_byod',
//                 'username' => 'u1792164_byod',
//                 'password' => 'u1792164_byod'