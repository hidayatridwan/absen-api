<?php

namespace RidwanHidayat\Absen\API\Repository;

use PDO;
use RidwanHidayat\Absen\API\Domain\Karyawan;

class KaryawanRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    protected function appendParams(Karyawan $karyawan): array
    {
        return [
            $karyawan->nama,
            $karyawan->jenisKelamin,
            $karyawan->tempatLahir,
            $karyawan->tanggalLahir,
            $karyawan->noHp,
            $karyawan->alamat,
            $karyawan->email,
            $karyawan->divisi,
            $karyawan->jabatan
        ];
    }

    public function fetchToObject(mixed $row): Karyawan
    {
        $karyawan = new Karyawan();
        $karyawan->id = $row['id'];
        $karyawan->nik = $row['nik'];
        $karyawan->nama = $row['nama'];
        $karyawan->jenisKelamin = $row['jenis_kelamin'];
        $karyawan->tempatLahir = $row['tempat_lahir'];
        $karyawan->tanggalLahir = $row['tanggal_lahir'];
        $karyawan->noHp = $row['no_hp'];
        $karyawan->alamat = $row['alamat'];
        $karyawan->email = $row['email'];
        $karyawan->divisi = $row['divisi'];
        $karyawan->jabatan = $row['jabatan'];
        $karyawan->password = $row['password'];
        $karyawan->token = $row['token'];
        $karyawan->facePoint = $row['face_point'];
        $karyawan->aktif = $row['aktif'];
        $karyawan->createdAt = $row['created_at'];
        $karyawan->updatedAt = $row['updated_at'];

        return $karyawan;
    }

    public function findAll(): array
    {
        try {
            $statement = $this->connection->query("SELECT *
            FROM `m_karyawan`;");

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($result as $row) {
                $karyawan = $this->fetchToObject($row);
                $data[] = $karyawan;
            }

            return $data;
        } finally {
            $statement->closeCursor();
        }
    }

    public function findByNIK(string $nik): ?Karyawan
    {
        try {
            $statement = $this->connection->prepare("SELECT *
            FROM `m_karyawan`
            WHERE `nik` = ?;
            ");

            $statement->execute([$nik]);

            if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $this->fetchToObject($row);
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function save(Karyawan $karyawan): Karyawan
    {
        $statement = $this->connection->prepare("INSERT INTO `m_karyawan`
            (
                `nik`,
                `nama`,
                `jenis_kelamin`,
                `tempat_lahir`,
                `tanggal_lahir`,
                `no_hp`,
                `alamat`,
                `email`,
                `divisi`,
                `jabatan`,
                `created_at`
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, unix_timestamp()
            );
        ");

        $params = $this->appendParams($karyawan);
        array_unshift($params, $karyawan->nik);

        $statement->execute($params);

        return $karyawan;
    }

    public function update(Karyawan $karyawan): Karyawan
    {
        $statement = $this->connection->prepare("UPDATE `m_karyawan`
            SET `nama` = ?,
            `jenis_kelamin` = ?,
            `tempat_lahir` = ?,
            `tanggal_lahir` = ?,
            `no_hp` = ?,
            `alamat` = ?,
            `email` = ?,
            `divisi` = ?,
            `jabatan` = ?,
            `updated_at` = unix_timestamp()
            WHERE `nik` = ?;"
        );

        $params = $this->appendParams($karyawan);
        $params[] = $karyawan->nik;

        $statement->execute($params);

        return $karyawan;
    }

    public function deleteByNIK(string $nik): int
    {
        $statement = $this->connection->prepare("DELETE FROM `m_karyawan`
            WHERE `nik` = ?;"
        );

        $statement->execute([$nik]);

        return $statement->rowCount();
    }

    public function updatePassword(Karyawan $karyawan): int
    {
        $statement = $this->connection->prepare("UPDATE `m_karyawan`
            SET `password` = ?,
            `updated_at` = unix_timestamp()
            WHERE `nik` = ?;
        ");

        $statement->execute([
            $karyawan->password,
            $karyawan->nik
        ]);

        return $statement->rowCount();
    }

    public function updateFacePoint(Karyawan $karyawan): int
    {
        $statement = $this->connection->prepare("UPDATE `m_karyawan`
            SET `face_point` = ?,
            `updated_at` = unix_timestamp()
            WHERE `nik` = ?;
        ");

        $statement->execute([
            $karyawan->facePoint,
            $karyawan->nik
        ]);

        return $statement->rowCount();
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM `m_karyawan`;");
    }
}