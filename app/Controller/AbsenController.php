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
        header('Content-Type: application/json; charset=utf-8');
    }

    public function findAll(): void
    {
        if (!isset($_GET['startDate']) || !isset($_GET['endDate'])) {
            http_response_code(400);
            $response = [
                'error' => 'Start - End date required.'
            ];
            echo json_encode($response);
            die;
        }
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        $result = $this->absenService->findAll($startDate, $endDate);

        http_response_code(200);
        echo json_encode($result);
    }

    public function findByNIK(string $nik): void
    {
        $period = isset($_GET['period']) ? $_GET['period'] : date('Y-m-d');
        $result = $this->absenService->findByNIK($nik, $period);

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
