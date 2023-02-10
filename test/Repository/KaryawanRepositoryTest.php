<?php

namespace RidwanHidayat\Absen\API\Repository;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Karyawan;

class KaryawanRepositoryTest extends TestCase
{

    private KaryawanRepository $karyawanRepository;
    private AbsenRepository $absenRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->karyawanRepository = new KaryawanRepository($connection);
        $this->absenRepository = new AbsenRepository($connection);

        $this->absenRepository->deleteAll();
        $this->karyawanRepository->deleteAll();
    }

    protected function saveKaryawan(string $nik = '2200000001'): Karyawan
    {
        $karyawan = new Karyawan();
        $karyawan->nik = $nik;
        $karyawan->nama = 'Ridwan Hidayat';
        $karyawan->tanggalLahir = '1993-04-07';
        $karyawan->jenisKelamin = 'L';
        $karyawan->tempatLahir = 'Sumedang';
        $karyawan->noHp = '083141418173';
        $karyawan->alamat = 'Jl inhofftank';
        $karyawan->email = 'ridwan.nurulhidayat@gmail.com';
        $karyawan->divisi = 'IT';
        $karyawan->jabatan = 'Programmer';
        $this->karyawanRepository->save($karyawan);

        return $karyawan;
    }

    public function testSaveSuccess()
    {
        $karyawan = $this->saveKaryawan();

        $result = $this->karyawanRepository->findByNIK($karyawan->nik);
        self::assertEquals($karyawan->nik, $result->nik);
        self::assertEquals($karyawan->nama, $result->nama);
        self::assertEquals($karyawan->tanggalLahir, $result->tanggalLahir);
    }

    public function testFindByNIKNotFound()
    {
        $karyawan = $this->karyawanRepository->findByNIK('NotFound');
        self::assertNull($karyawan);
    }

    public function testFindAll()
    {
        $this->saveKaryawan();
        $this->saveKaryawan('2200000002');

        $response = $this->karyawanRepository->findAll();
        self::assertCount(2, $response);
    }

    public function testUpdateSuccess()
    {
        $this->saveKaryawan();

        $karyawan = new Karyawan();
        $karyawan->nik = '2200000001';
        $karyawan->nama = 'Dono';
        $karyawan->tanggalLahir = '1993-04-07';
        $karyawan->jenisKelamin = 'L';
        $karyawan->tempatLahir = 'KBB';
        $karyawan->noHp = '083141418173';
        $karyawan->alamat = 'Jl inhofftank';
        $karyawan->email = 'ridwan.nurulhidayat@gmail.com';
        $karyawan->divisi = 'IT';
        $karyawan->jabatan = 'Programmer';
        $this->karyawanRepository->update($karyawan);

        $result = $this->karyawanRepository->findByNIK($karyawan->nik);

        self::assertEquals($karyawan->nama, $result->nama);
        self::assertEquals($karyawan->tempatLahir, $result->tempatLahir);
    }

    public function testDeleteByNIK()
    {
        $karyawan = $this->saveKaryawan();

        $result = $this->karyawanRepository->deleteByNIK($karyawan->nik);
        self::assertEquals(1, $result);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->saveKaryawan();

        $karyawan = new Karyawan();
        $karyawan->nik = '2200000001';
        $karyawan->password = password_hash('4377', PASSWORD_BCRYPT);
        $karyawan->updatedAt = time();
        $response = $this->karyawanRepository->updatePassword($karyawan);

        self::assertEquals(1, $response);
    }

    public function testUpdateTokenSuccess()
    {
        $this->saveKaryawan();

        $karyawan = new Karyawan();
        $karyawan->nik = '2200000001';
        $karyawan->token = md5(date('YmdHis'));
        $karyawan->updatedAt = time();
        $response = $this->karyawanRepository->updateToken($karyawan);

        self::assertEquals(1, $response);
    }

    public function testUpdateFacePointSuccess()
    {
        $this->saveKaryawan();

        $karyawan = new Karyawan();
        $karyawan->nik = '2200000001';
        $karyawan->facePoint = '[-000]';
        $karyawan->updatedAt = time();
        $response = $this->karyawanRepository->updateFacePoint($karyawan);

        self::assertEquals(1, $response);
    }
}