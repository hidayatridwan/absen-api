<?php

namespace RidwanHidayat\Absen\API\Controller;

use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Repository\KordinatRepository;
use RidwanHidayat\Absen\API\Service\KordinatService;

class KordinatController
{
    private KordinatService $kordinatService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $kordinatRepository = new KordinatRepository($connection);
        $this->kordinatService = new KordinatService($kordinatRepository);
        header('Content-Type: application/json; charset=utf-8');
    }

    public function findKordinatAktif(string $nik): void
    {
        $result = $this->kordinatService->findKordinatAktif($nik);

        $response = [
            'result' => $result
        ];
        http_response_code(200);

        echo json_encode($response);
    }

    public function updateKordinatAktif(string $nama): void
    {
        $result = $this->kordinatService->updateKordinatAktif($nama);

        $response = [
            'result' => $result
        ];
        http_response_code(200);

        echo json_encode($response);
    }
}
