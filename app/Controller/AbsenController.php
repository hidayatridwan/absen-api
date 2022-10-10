<?php

namespace RidwanHidayat\Absen\API\Controller;

use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Helper\Helper;
use RidwanHidayat\Absen\API\Model\AbsenRequest;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use RidwanHidayat\Absen\API\Service\AbsenService;

class AbsenController
{

    private AbsenService $absenService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $absenRepository = new AbsenRepository($connection);
        $this->absenService = new AbsenService($absenRepository);
        error_reporting(0);
        header('Content-Type: application/json; charset=utf-8');
    }

    public function findAll(): void
    {
        $result = $this->absenService->findAll();

        $result = [
            'result' => $result
        ];

        http_response_code(200);
        echo json_encode($result);
    }

    public function findByNIK(string $nik): void
    {
        $result = $this->absenService->findByNIK($nik);

        $response = [
            'result' => $result
        ];
        http_response_code(200);

        echo json_encode($response);
    }

    public function save(): void
    {
        Helper::parseToPost();
        $request = new AbsenRequest();
        $request->nik = $_POST['nik'];

        try {
            $result = $this->absenService->save($request);

            $response = [
                'result' => $result->absen
            ];
            http_response_code(201);
        } catch (ValidationException $exception) {
            $response = [
                'error' => $exception->getMessage()
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }
}