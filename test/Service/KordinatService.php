<?php

namespace RidwanHidayat\Absen\API\Service;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Repository\KordinatRepository;

class KordinatServiceTest extends TestCase
{

    private KordinatService $kordinatService;
    private KordinatRepository $kordinatRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->kordinatRepository = new KordinatRepository($connection);
        $this->kordinatService = new KordinatService($this->kordinatRepository);
    }

    public function testFindByNama()
    {
        $this->saveKaryawan();

        $this->saveAbsen();

        $result = $this->kordinatService->findByNama('2200000001');

        self::assertIsArray($result);
        self::assertNotNull($result);
    }
}