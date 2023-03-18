<?php

namespace RidwanHidayat\Absen\API\Service;

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;
use RidwanHidayat\Absen\API\Repository\TokenRepository;

class TokenServiceTest extends TestCase
{

    private TokenService $tokenService;
    private KaryawanRepository $karyawanRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->karyawanRepository = new KaryawanRepository($connection);
        $tokenRepository = new TokenRepository($connection);
        $this->tokenService = new TokenService($tokenRepository);
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

    public function testUpdateTokenSuccess()
    {
        $this->saveKaryawan();

        $response = $this->tokenService->save('2200000001', md5(date('YmdHis')));

        self::assertEquals(1, $response);
    }

    public function testGetTokenSuccess()
    {

        $this->saveKaryawan();
        $token = md5(date('YmdHis'));
        $this->tokenService->save('2200000001', $token);
        $response = $this->tokenService->get($token);
        self::assertEquals($token, $response);
    }

    public function testDeleteTokenSuccess()
    {

        $this->saveKaryawan();
        $this->tokenService->save('2200000001', md5(date('YmdHis')));
        $response = $this->tokenService->delete('2200000001');
        self::assertEquals(1, $response);
    }
}