<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Domain\Absen;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Model\AbsenRequest;
use RidwanHidayat\Absen\API\Model\AbsenResponse;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;

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
            throw new ValidationException('Wrong parameters');
        } else if ($request->nik == null or trim($request->nik) == '') {
            throw new ValidationException('NIK do not blank');
        }
    }

    public function findAll(string $period): array
    {
        return $this->absenRepository->findAll($period);
    }

    public function findByNIK(string $nik, string $period): array
    {
        return $this->absenRepository->findByNIK($nik, $period);
    }

    public function save(AbsenRequest $request): AbsenResponse
    {
        $this->validationAbsenRequest($request);

        $absen = new Absen();
        $absen->nik = $request->nik;

        $result = $this->absenRepository->save($absen);

        $response = new AbsenResponse();
        $response->absen = $result;

        return $response;
    }
}
