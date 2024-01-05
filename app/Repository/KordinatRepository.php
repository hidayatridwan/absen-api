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
        $this->connection->query("set time_zone = '+07:00';");
    }

    public function get(string $nik): array
    {
        try {
            $statement = $this->connection->prepare("SELECT
                    *,
                    (SELECT COUNT(*) FROM t_absen WHERE nik = ? 
                    AND DATE(FROM_UNIXTIME(jam_absen)) = CURRENT_DATE LIMIT 1) AS jumlah_absen
                FROM
                    m_kordinat
                WHERE aktif = 'Y';
            ");

            $statement->execute([$nik]);

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $kordinat = new Kordinat();
                $kordinat->id = $row['id'];
                $kordinat->nama = $row['nama'];
                $kordinat->lat = $row['lat'];
                $kordinat->lng = $row['lng'];
                $kordinat->jumlahAbsen = $row['jumlah_absen'];
                $data[] = $kordinat;
            }

            return $data;
        } finally {
            $statement->closeCursor();
        }
    }

    public function update(Kordinat $kordinat): bool
    {
        $statementReset = $this->connection->prepare(
            "UPDATE `m_kordinat`
            SET `aktif` = 'N'
            WHERE `nama` != 'initial';"
        );
        $statementReset->execute();

        $statement = $this->connection->prepare(
            "UPDATE `m_kordinat`
            SET `aktif` = 'Y'
            WHERE `nama` = ?;"
        );

        $params[] = $kordinat->nama;

        return $statement->execute($params);
    }
}
