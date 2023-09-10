<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Domain\Kordinat;
use RidwanHidayat\Absen\API\Repository\KordinatRepository;

class KordinatService
{
    private KordinatRepository $kordinatRepository;

    public function __construct(KordinatRepository $kordinatRepository)
    {
        $this->kordinatRepository = $kordinatRepository;
    }

    public function findKordinatAktif(string $nik): array
    {
        return $this->kordinatRepository->get($nik);
    }

    public function updateKordinatAktif(string $nama): bool
    {
        $kordinat = new Kordinat();
        $kordinat->nama = $nama;
        return $this->kordinatRepository->update($kordinat);
    }
}
