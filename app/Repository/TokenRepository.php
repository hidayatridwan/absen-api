<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;

class TokenRepository
{

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(string $nik, string $token): int
    {
        $statement = $this->connection->prepare("UPDATE `m_karyawan`
        SET `token` = ?
        WHERE `nik` = ?;");

        $statement->execute([
            $token,
            $nik
        ]);

        return $statement->rowCount();
    }

    public function get(?string $token): ?string
    {
        try {
            $statement = $this->connection->prepare("SELECT `token`
            FROM `m_karyawan`
            WHERE `token` = ?;
            ");

            $statement->execute([$token]);

            if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $row['token'];
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function delete(string $nik): int
    {
        $statement = $this->connection->prepare("UPDATE `m_karyawan`
        SET `token` = NULL
            WHERE `nik` = ?;"
        );

        $statement->execute([$nik]);

        return $statement->rowCount();
    }
}