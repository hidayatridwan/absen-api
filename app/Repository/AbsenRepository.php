<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;
use PDOStatement;
use RidwanHidayat\Absen\API\Domain\Absen;

class AbsenRepository
{

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchToObject(PDOStatement $statement): array
    {
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
    }

    public function findAll(): array
    {
        try {
            $statement = $this->connection->query("SELECT *
                FROM `t_absen`;
            ");

            return $this->fetchToObject($statement);
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByNIK(string $nik): array
    {
        try {
            $statement = $this->connection->prepare("SELECT 
                nik,
                MIN(jam_absen) AS jam_datang,
                MAX(jam_absen) AS jam_pulang
            FROM
                t_absen
            WHERE `nik` = ?
            GROUP BY nik,
            DATE_FORMAT(FROM_UNIXTIME(jam_absen), '%Y-%m-%d');
            ");

            $statement->execute([$nik]);

            return $this->fetchToObject($statement);
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
                ?,
                unix_timestamp()
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
