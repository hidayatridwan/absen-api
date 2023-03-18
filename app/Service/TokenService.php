<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Repository\TokenRepository;

class TokenService
{
    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function save(string $nik, string $token): int
    {
        return $this->tokenRepository->save($nik, $token);
    }

    public function get(?string $token): ?string
    {
        $token = $this->tokenRepository->get($token);
        if ($token == null) {
            return null;
        }

        return $token;
    }

    public function delete(string $nik): int
    {
        return $this->tokenRepository->delete($nik);
    }
}