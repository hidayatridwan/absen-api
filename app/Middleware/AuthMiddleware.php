<?php

namespace RidwanHidayat\Absen\API\Middleware;

use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Repository\TokenRepository;
use RidwanHidayat\Absen\API\Service\TokenService;

class AuthMiddleware implements Middleware
{

    private TokenService $tokenService;

    public function __construct()
    {
        $tokenRepository = new TokenRepository(Database::getConnection());
        $this->tokenService = new TokenService($tokenRepository);
    }

    function before(): void
    {
        $token = getallheaders()['token'] ?? null;
        $result = $this->tokenService->get($token);
        if ($result == null) {

            header('Content-Type: application/json; charset=utf-8');

            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            die;
        }
    }
}