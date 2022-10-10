<?php

namespace RidwanHidayat\Absen\API\Controller;

require_once __DIR__ . '/../Helper/helper.php';

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Absen;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;

class AbsenControllerTest extends TestCase
{
    private KaryawanRepository $karyawanRepository;
    private AbsenRepository $absenRepository;
    private AbsenController $absenController;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->karyawanRepository = new KaryawanRepository($connection);
        $this->absenRepository = new AbsenRepository($connection);
        $this->absenController = new AbsenController();

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

    public function testSaveSuccess()
    {
        $this->saveKaryawan();

        $_POST['nik'] = '2200000001';
        $_POST['jam_absen'] = time();

        $this->absenController->save();

        self::assertEquals(201, http_response_code());
    }

    public function testSaveFailed()
    {
        $_POST['nik'] = '';
        $_POST['jam_absen'] = time();
        $this->absenController->save();

        self::assertEquals(400, http_response_code());
    }

    public function testFindByNIK()
    {
        $this->saveKaryawan();

        $absen = new Absen();
        $absen->nik = '2200000001';
        $absen->jam_absen = time();
        $this->absenRepository->save($absen);

        $this->absenController->findByNIK('2200000001');

        self::assertEquals(200, http_response_code());
    }

//    public function testFindByNIKNotFound()
//    {
//        $this->absenController->findByNIK('NotFound');
//
//        self::assertEquals(404, http_response_code());
//    }

    public function testFindAll()
    {
        $this->saveKaryawan();

        $absen = new Absen();
        $absen->nik = '2200000001';
        $absen->jam_absen = time();
        $this->absenRepository->save($absen);

        $this->absenController->findAll();

        self::assertEquals(200, http_response_code());
        $this->expectOutputRegex('["id":"]');
    }
}