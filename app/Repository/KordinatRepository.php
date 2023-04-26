<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;
use RidwanHidayat\Absen\API\Domain\Kordinat;

class KordinatRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function get(): array
    {
        try {
            $statement = $this->connection->query("SELECT
                *
            FROM
                m_kordinat
            WHERE aktif = 'Y';");

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $kordinat = new Kordinat();
                $kordinat->nama = $row['nama'];
                $kordinat->lat = $row['lat'];
                $kordinat->lng = $row['lng'];
                $data[] = $kordinat;
            }

            return $data;
        } finally {
            $statement->closeCursor();
        }
    }
}