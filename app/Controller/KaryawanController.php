<?php

namespace RidwanHidayat\Absen\API\Controller;

use Exception;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Helper\Helper;
use RidwanHidayat\Absen\API\Model\KaryawanRequest;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;
use RidwanHidayat\Absen\API\Service\KaryawanService;

class KaryawanController
{
    private KaryawanService $karyawanService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $karyawanRepository = new KaryawanRepository($connection);
        $this->karyawanService = new KaryawanService($karyawanRepository);
        error_reporting(0);
        header('Content-Type: application/json; charset=utf-8');
    }

    protected function appendRequest(): KaryawanRequest
    {
        $request = new KaryawanRequest();
        $request->nik = $_POST['nik'];
        $request->nama = $_POST['nama'];
        $request->jenisKelamin = $_POST['jenisKelamin'];
        $request->tempatLahir = $_POST['tempatLahir'];
        $request->tanggalLahir = $_POST['tanggalLahir'];
        $request->noHp = $_POST['noHp'];
        $request->alamat = $_POST['alamat'];
        $request->email = $_POST['email'];
        $request->divisi = $_POST['divisi'];
        $request->jabatan = $_POST['jabatan'];

        return $request;
    }

    public function findAll(): void
    {
        $result = $this->karyawanService->findAll();

        $result = [
            'result' => $result
        ];

        http_response_code(200);
        echo json_encode($result);
    }

    public function findByNIK(string $nik): void
    {
        $result = $this->karyawanService->findByNIK($nik);

        if ($result != null) {
            $response = [
                'result' => $result
            ];
            http_response_code(200);
        } else {
            $response = [
                'message' => 'Data karyawan tidak ditemukan.'
            ];
            http_response_code(404);
        }

        echo json_encode($response);
    }

    public function save(): void
    {
        Helper::parseToPost();

        $request = $this->appendRequest();

        try {
            $response = $this->karyawanService->save($request);

            $response = [
                'result' => $response->karyawan
            ];
            http_response_code(201);
        } catch (Exception $exception) {
            $response = [
                'error' => $exception->getMessage()
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }

    public function update(): void
    {
        Helper::parseToPost();

        $request = $this->appendRequest();

        try {
            $response = $this->karyawanService->update($request);
            $response = [
                'result' => $response->karyawan
            ];
            http_response_code(200);
        } catch (Exception $exception) {
            $response = [
                'error' => $exception->getMessage()
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }

    public function delete(): void
    {
        Helper::parseToPost();

        $request = new KaryawanRequest();
        $request->nik = $_POST['nik'];

        $result = $this->karyawanService->delete($request->nik);

        if ($result > 0) {
            $response = [
                'message' => 'Berhasil dihapus'
            ];
            http_response_code(200);
        } else {
            $response = [
                'error' => 'Gagal dihapus'
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }

    public function updatePassword(): void
    {
        Helper::parseToPost();

        $request = new KaryawanRequest();
        $request->nik = $_POST['nik'];
        $request->password = $_POST['password'];

        try {
            $this->karyawanService->updatePassword($request);
            $response = [
                'message' => 'Berhasil di update'
            ];
            http_response_code(200);
        } catch (Exception $exception) {
            $response = [
                'error' => $exception->getMessage()
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }

    public function login(): void
    {
        Helper::parseToPost();

        $request = new KaryawanRequest();
        $request->nik = $_POST['nik'];
        $request->password = $_POST['password'];

        try {
            $response = $this->karyawanService->login($request);
            $response = [
                'result' => $response->karyawan
            ];
            http_response_code(200);
        } catch (Exception $exception) {
            $response = [
                'error' => $exception->getMessage()
            ];
            http_response_code(400);
        }

        echo json_encode($response);
    }
}
