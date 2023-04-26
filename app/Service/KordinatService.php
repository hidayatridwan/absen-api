<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Repository\KordinatRepository;

class KordinatService
{
    private KordinatRepository $kordinatRepository;

    public function __construct(KordinatRepository $kordinatRepository)
    {
        $this->kordinatRepository = $kordinatRepository;
    }

    public function findKordinatAktif(): array
    {
        return $this->kordinatRepository->get();
    }
}