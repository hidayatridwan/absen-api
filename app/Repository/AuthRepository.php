<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;

class AuthRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function apiKey(string $key): string
    {
        try {
            $statement = $this->connection->prepare("SELECT `value`
                FROM
                    `auth`
                WHERE `key` = ?;
            ");

            $statement->execute([$key]);

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return $row['value'];
        } finally {
            $statement->closeCursor();
        }
    }
}
