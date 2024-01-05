<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;
use RidwanHidayat\Absen\API\Domain\Absen;

class AbsenRepository
{

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->connection->query("set time_zone = '+07:00';");
    }

    public function findAll(string $period): array
    {
        try {
            $startDate = $period . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $endDate = $endDate . ' 23:59:59';

            $this->connection->query("SELECT @startDate := UNIX_TIMESTAMP('$startDate'),
            @endDate := UNIX_TIMESTAMP('$endDate');");
            $statement = $this->connection->query("SELECT
                    t1.nik,
                    t2.nama,
                    t2.divisi,
                    t2.jabatan,
                    FROM_UNIXTIME(MIN(t1.jam_absen)) AS jam_datang,
                    FROM_UNIXTIME(MAX(t1.jam_absen)) AS jam_pulang
                FROM
                    t_absen as t1
                LEFT JOIN m_karyawan as t2 on t1.nik = t2.nik
                WHERE t1.`jam_absen` BETWEEN @startDate AND @endDate
                GROUP BY
                t1.nik,
                DATE_FORMAT(FROM_UNIXTIME(t1.jam_absen), '%Y-%m-%d')
                ORDER BY DATE_FORMAT(FROM_UNIXTIME(jam_absen), '%Y-%m-%d');
            ");

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $absen = new Absen();
                $absen->nik = $row['nik'];
                $absen->nama = $row['nama'];
                $absen->divisi = $row['divisi'];
                $absen->jabatan = $row['jabatan'];
                $absen->jamDatang = $row['jam_datang'];
                $absen->jamPulang = $row['jam_pulang'];
                $data[] = $absen;
            }

            return $data;
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByNIK(string $nik, string $period): array
    {
        try {
            $startDate = date('Y-m-01', strtotime($period));
            $endDate = $period . ' 23:59:59';

            $this->connection->query("SELECT
            @startDate := UNIX_TIMESTAMP('$startDate'),
            @endDate := UNIX_TIMESTAMP('$endDate');");

            $statement = $this->connection->prepare("SELECT
                    nik,
                    FROM_UNIXTIME(MIN(jam_absen)) AS jam_datang,
                    FROM_UNIXTIME(MAX(jam_absen)) AS jam_pulang
                FROM
                    t_absen
                WHERE `nik` = ?
                AND `jam_absen` BETWEEN @startDate AND @endDate
                GROUP BY nik,
                DATE_FORMAT(FROM_UNIXTIME(jam_absen), '%Y-%m-%d')
                ORDER BY DATE_FORMAT(FROM_UNIXTIME(jam_absen), '%Y-%m-%d');
            ");

            $statement->execute([$nik]);

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $absen = new Absen();
                $absen->nik = $row['nik'];
                $absen->jamDatang = $row['jam_datang'];
                $absen->jamPulang = $row['jam_pulang'];
                $data[] = $absen;
            }

            return $data;
        } finally {
            $statement->closeCursor();
        }
    }

    public function save(Absen $absen): Absen
    {
        $statement = $this->connection->prepare("INSERT INTO `t_absen`
            (
                `nik`,
                `jam_absen`
            )
            VALUES
            (
                ?, unix_timestamp()
            );
        ");

        $statement->execute([
            $absen->nik
        ]);

        return $absen;
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM `t_absen`;");
    }
}
