<?php

namespace RidwanHidayat\Absen\API\Controller;

require_once __DIR__ . '/../Helper/helper.php';

use PHPUnit\Framework\TestCase;

class KordinatControllerTest extends TestCase
{
    private KordinatController $kordinatController;

    protected function setUp(): void
    {
        $this->kordinatController = new KordinatController();
    }

    public function testFindByNama()
    {
        $this->kordinatController->findKordinatAktif();

        self::assertEquals(200, http_response_code());
    }

}