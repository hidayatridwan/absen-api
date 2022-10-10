<?php

namespace RidwanHidayat\Absen\API\Service;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Model\KaryawanRequest;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;
use function PHPUnit\Framework\assertEquals;

class KaryawanServiceTest extends TestCase
{

    private AbsenRepository $absenRepository;
    private KaryawanRepository $karyawanRepository;
    private KaryawanService $karyawanService;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->karyawanRepository = new KaryawanRepository($connection);
        $this->absenRepository = new AbsenRepository($connection);
        $this->karyawanService = new KaryawanService($this->karyawanRepository);

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

    public function testSaveSuccess()
    {
        $request = new KaryawanRequest();
        $request->nik = '2200000001';
        $request->nama = 'Ridwan Hidayat';
        $request->tanggalLahir = '1993-04-07';
        $request->jenisKelamin = 'L';
        $request->tempatLahir = 'Sumedang';
        $request->noHp = '083141418173';
        $request->alamat = 'Jl inhofftank';
        $request->email = 'ridwan.nurulhidayat@gmail.com';
        $request->divisi = 'IT';
        $request->jabatan = 'Programmer';
        $request->createdAt = time();
        $response = $this->karyawanService->save($request);

        self::assertEquals($request->nik, $response->karyawan->nik);
        self::assertEquals($request->nama, $response->karyawan->nama);
        self::assertEquals($request->tanggalLahir, $response->karyawan->tanggalLahir);
    }

    public function testSaveFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new KaryawanRequest();
        $request->nik = '';
        $request->nama = '';
        $this->karyawanService->save($request);
    }

    public function testSaveDuplicate()
    {
        $this->saveKaryawan();

        $this->expectException(ValidationException::class);

        $request = new KaryawanRequest();
        $request->nik = '2200000001';
        $request->nama = 'Ridwan Hidayat';
        $request->tanggalLahir = '1993-04-07';
        $this->karyawanService->save($request);
    }

    public function testFindByNIK()
    {
        $karyawan = $this->saveKaryawan();

        $response = $this->karyawanService->findByNIK($karyawan->nik);

        self::assertEquals($karyawan->nik, $response->nik);
        self::assertEquals($karyawan->nama, $response->nama);
        self::assertEquals($karyawan->tanggalLahir, $response->tanggalLahir);
    }

    public function testFindAll()
    {
        $this->saveKaryawan();
        $this->saveKaryawan('2200000002');

        $response = $this->karyawanService->findAll();

        self::assertCount(2, $response);
    }

    public function testUpdateSuccess()
    {
        $karyawan = $this->saveKaryawan();

        $request = new KaryawanRequest();
        $request->nik = '2200000001';
        $request->nama = 'Dono';
        $request->tanggalLahir = '2000-04-07';
        $request->updatedAt = time();
        $response = $this->karyawanService->update($request);

        self::assertNotEquals($karyawan->nama, $response->karyawan->nama);
        self::assertNotEquals($karyawan->tanggalLahir, $response->karyawan->tanggalLahir);
    }

    public function testUpdateFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new KaryawanRequest();
        $request->nik = 'NotFound';
        $this->karyawanService->update($request);
    }

    public function testDeleteSuccess()
    {
        $karyawan = $this->saveKaryawan();

        $response = $this->karyawanService->delete($karyawan->nik);

        self::assertEquals(1, $response);
    }

    public function testDeleteFailed()
    {
        $karyawan = new Karyawan();
        $karyawan->nik = 'NotFound';

        $response = $this->karyawanService->delete($karyawan->nik);

        self::assertEquals(0, $response);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->saveKaryawan();

        $request = new KaryawanRequest();
        $request->nik = '2200000001';
        $request->password = password_hash('4377', PASSWORD_BCRYPT);
        $request->updatedAt = time();

        $response = $this->karyawanService->updatePassword($request);

        self::assertEquals(1, $response);
    }

    public function testLoginSuccess()
    {
        $this->saveKaryawan();

        $request = new KaryawanRequest();
        $request->nik = '2200000001';
        $request->password = '4377';
        $request->updatedAt = time();
        $this->karyawanService->updatePassword($request);

        $request2 = new KaryawanRequest();
        $request2->nik = '2200000001';
        $request2->password = '4377';

        $response = $this->karyawanService->login($request2);

        self::assertEquals($request2->nik, $response->karyawan->nik);
        self::assertNotEquals($request2->password, $response->karyawan->password);
        self::assertTrue(password_verify($request2->password, $response->karyawan->password));
    }
}