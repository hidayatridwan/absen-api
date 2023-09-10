<?php

namespace RidwanHidayat\Absen\API\Middleware;

use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Repository\AuthRepository;

class AuthMiddleware implements Middleware
{
    private AuthRepository $authRepository;

    public function __construct()
    {
        $connection = Database::getConnection();
        $this->authRepository = new AuthRepository($connection);
    }

    function before(): void
    {
        $headers = array_change_key_case(getallheaders());
        $key = 'x-api-key';
        $keyRequest = $headers[$key] ?? null;
        $keyResponse = $this->authRepository->apiKey($key);

        if ($keyRequest != $keyResponse) {

            header('Content-Type: application/json; charset=utf-8');

            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            die;
        }
    }
}
