<?php

namespace RidwanHidayat\Absen\API\Repository;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Absen;
use RidwanHidayat\Absen\API\Domain\Karyawan;

class AbsenRespositoryTest extends TestCase
{

    private AbsenRepository $absenRepository;
    private KaryawanRepository $karyawanRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->absenRepository = new AbsenRepository($connection);
        $this->karyawanRepository = new KaryawanRepository($connection);

        $this->absenRepository->deleteAll();
        $this->karyawanRepository->deleteAll();
    }

    protected function saveKaryawan(): void
    {
        $karyawan = new Karyawan();
        $karyawan->nik = '2200000001';
        $karyawan->nama = 'Ridwan Hidayat';
        $karyawan->tanggalLahir = '1993-04-07';
        $karyawan->jenisKelamin = 'L';
        $karyawan->tempatLahir = 'Sumedang';
        $karyawan->noHp = '083141418173';
        $karyawan->alamat = 'Jl inhofftank';
        $karyawan->email = 'ridwan.nurulhidayat@gmail.com';
        $karyawan->divisi = 'IT';
        $karyawan->jabatan = 'Programmer';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);
    }

    protected function saveAbsen(): Absen
    {
        $absen = new Absen();
        $absen->nik = '2200000001';
        $absen->jam_absen = time();
        $this->absenRepository->save($absen);

        return $absen;
    }

    public function testFindAll()
    {
        $this->saveKaryawan();

        $this->saveAbsen();
        $this->saveAbsen();

        $result = $this->absenRepository->findAll();
        self::assertCount(2, $result);
    }

    public function testSaveSuccess()
    {
        $this->saveKaryawan();

        $absen = $this->saveAbsen();

        $result = $this->absenRepository->findByNIK($absen->nik);

        self::assertEquals($absen->nik, $result[0]->nik);
        self::assertCount(1, $result);
    }
}
