<?php

namespace RidwanHidayat\Absen\API\Middleware;

class AuthMiddleware implements Middleware
{

    function before(): void
    {
        $token = getallheaders()['token'] ?? null;

        if ($token != 'ridwan123') {

            header('Content-Type: application/json; charset=utf-8');

            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            die;
        }
    }
}
