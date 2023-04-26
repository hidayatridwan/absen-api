<?php

namespace RidwanHidayat\Absen\API\Repository;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;

class KordinatRepositoryTest extends TestCase
{
    private KordinatRepository $kordinatRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->kordinatRepository = new KordinatRepository($connection);
    }

    public function testGetKordinatSuccess()
    {
        $kordinat = $this->kordinatRepository->get('office');
        self::assertNotNull($kordinat);
        self::assertIsArray($kordinat);
    }
}