<?php

namespace RidwanHidayat\Absen\API\Service;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Absen;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Model\AbsenRequest;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;

class AbsenServiceTest extends TestCase
{

    private AbsenService $absenService;
    private AbsenRepository $absenRepository;
    private KaryawanRepository $karyawanRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->absenRepository = new AbsenRepository($connection);
        $this->karyawanRepository=new KaryawanRepository($connection);
        $this->absenService = new AbsenService($this->absenRepository);

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
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        return $karyawan;
    }

    protected function saveAbsen(): void
    {
        $absen = new Absen();
        $absen->nik = '2200000001';
        $this->absenRepository->save($absen);
    }

    public function testSaveSuccess()
    {
        $this->saveKaryawan();

        $request = new AbsenRequest();
        $request->nik = '2200000001';
        $response = $this->absenService->save($request);

        self::assertEquals($request->nik, $response->absen->nik);
    }

    public function testSaveFailed()
    {
        $this->expectException(ValidationException::class);
        $request = new AbsenRequest();
        $request->nik = '';
        $this->absenService->save($request);
    }

    public function testFindAll()
    {
        $this->saveKaryawan();

        $this->saveAbsen();

        $result = $this->absenService->findAll();

        self::assertCount(1, $result);
    }
}