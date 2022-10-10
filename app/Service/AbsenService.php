<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Domain\Absen;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Model\AbsenRequest;
use RidwanHidayat\Absen\API\Model\AbsenResponse;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use Exception;

class AbsenService
{

    private AbsenRepository $absenRepository;

    public function __construct(AbsenRepository $absenRepository)
    {
        $this->absenRepository = $absenRepository;
    }

    private function validationAbsenRequest(AbsenRequest $request): void
    {
        if (!isset($request->nik)) {
            throw new ValidationException('Parameter salah.');
        } else if ($request->nik == null or trim($request->nik) == '') {
            throw new ValidationException('NIK tidak boleh kosong.');
        }
    }

    public function findAll(): array
    {
        try {
            return $this->absenRepository->findAll();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findByNIK(string $nik): array
    {
        try {
            return $this->absenRepository->findByNIK($nik);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function save(AbsenRequest $request): AbsenResponse
    {
        $this->validationAbsenRequest($request);

        try {
            $absen = new Absen();
            $absen->nik = $request->nik;

            $result = $this->absenRepository->save($absen);

            $response = new AbsenResponse();
            $response->absen = $result;

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}