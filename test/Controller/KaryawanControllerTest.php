<?php

namespace RidwanHidayat\Absen\API\Controller;

require_once __DIR__ . '/../Helper/helper.php';

use PHPUnit\Framework\TestCase;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Repository\AbsenRepository;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;

class KaryawanControllerTest extends TestCase
{

    private KaryawanRepository $karyawanRepository;
    private KaryawanController $karyawanController;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $absenRepository = new AbsenRepository($connection);
        $this->karyawanRepository = new KaryawanRepository($connection);
        $this->karyawanController = new KaryawanController();

        $absenRepository->deleteAll();
        $this->karyawanRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $_POST['nik'] = '2200000001';
        $_POST['nama'] = 'Ridwan Hidayat';
        $_POST['tanggalLahir'] = '1993-04-07';
        $_POST['jenisKelamin'] = 'L';
        $_POST['tempatLahir'] = 'Sumedang';
        $_POST['noHp'] = '083141418173';
        $_POST['alamat'] = 'Jl inhofftank';
        $_POST['email'] = 'ridwan.nurulhidayat@gmail.com';
        $_POST['divisi'] = 'IT';
        $_POST['jabatan'] = 'Programmer';
        $_POST['facePoint'] = '[1,2,3,4,5]';

        $this->karyawanController->save();

        self::assertEquals(201, http_response_code());
    }

    public function testSaveFailed()
    {
        $_POST['nik'] = '';
        $_POST['nama'] = '';
        $_POST['tanggalLahir'] = '1993-04-07';
        $_POST['jenisKelamin'] = 'L';
        $_POST['tempatLahir'] = 'Sumedang';
        $_POST['noHp'] = '083141418173';
        $_POST['alamat'] = 'Jl inhofftank';
        $_POST['email'] = 'ridwan.nurulhidayat@gmail.com';
        $_POST['divisi'] = 'IT';
        $_POST['jabatan'] = 'Programmer';
        $_POST['facePoint'] = '[1,2,3,4,5]';
        $this->karyawanController->save();

        self::assertEquals(400, http_response_code());
    }

    public function testSaveDuplicate()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $_POST['nik'] = '2200000001';
        $_POST['nama'] = 'Ridwan Hidayat';
        $_POST['tanggalLahir'] = '1993-04-07';
        $_POST['jenisKelamin'] = 'L';
        $_POST['tempatLahir'] = 'Sumedang';
        $_POST['noHp'] = '083141418173';
        $_POST['alamat'] = 'Jl inhofftank';
        $_POST['email'] = 'ridwan.nurulhidayat@gmail.com';
        $_POST['divisi'] = 'IT';
        $_POST['jabatan'] = 'Programmer';
        $_POST['facePoint'] = '[1,2,3,4,5]';
        $this->karyawanController->save();

        self::assertEquals(400, http_response_code());
    }

    public function testFindByNIK()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $this->karyawanRepository->save($karyawan);

        $this->karyawanController->findByNIK('2200000001');

        self::assertEquals(200, http_response_code());
    }

    public function testFindByNIKNotFound()
    {
        $this->karyawanController->findByNIK('NotFound');

        self::assertEquals(404, http_response_code());
    }

    public function testFindAll()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $this->karyawanController->findAll();

        self::assertEquals(200, http_response_code());
        $this->expectOutputRegex('[{"]');
    }

    public function testUpdateSuccess()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $_POST['nik'] = '2200000001';
        $_POST['nama'] = 'DONO';
        $_POST['tanggalLahir'] = '1993-04-07';
        $_POST['jenisKelamin'] = 'L';
        $_POST['tempatLahir'] = 'Sumedang';
        $_POST['noHp'] = '083141418173';
        $_POST['alamat'] = 'Jl inhofftank';
        $_POST['email'] = 'ridwan.nurulhidayat@gmail.com';
        $_POST['divisi'] = 'IT';
        $_POST['jabatan'] = 'Programmer';
        $_POST['facePoint'] = '[1,2,3,4,5]';
        $this->karyawanController->update();

        self::assertEquals(200, http_response_code());
    }

    public function testUpdateFailed()
    {
        $_POST['nik'] = 'NotFound';
        $_POST['nama'] = 'DONO';
        $_POST['tanggalLahir'] = '1993-04-07';
        $_POST['jenisKelamin'] = 'L';
        $_POST['tempatLahir'] = 'Sumedang';
        $_POST['noHp'] = '083141418173';
        $_POST['alamat'] = 'Jl inhofftank';
        $_POST['email'] = 'ridwan.nurulhidayat@gmail.com';
        $_POST['divisi'] = 'IT';
        $_POST['jabatan'] = 'Programmer';
        $_POST['facePoint'] = '[1,2,3,4,5]';
        $this->karyawanController->update();

        self::assertEquals(400, http_response_code());
    }

    public function testDeleteSuccess()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $this->karyawanController->delete('2200000001');

        self::assertEquals(200, http_response_code());
    }

    public function testDeleteFailed()
    {
        $this->karyawanController->delete('NotFound');

        self::assertEquals(400, http_response_code());
    }

    public function testUpdatePasswordSuccess()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $_POST['nik'] = '2200000001';
        $_POST['oldPassword'] = '1993-04-07';
        $_POST['password'] = '4377';
        $this->karyawanController->updatePassword();

        self::assertEquals(200, http_response_code());
    }

    public function testLoginSuccess()
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
        $karyawan->facePoint = '[1,2,3,4,5]';
        $karyawan->createdAt = time();
        $this->karyawanRepository->save($karyawan);

        $karyawan->password = password_hash('4377', PASSWORD_BCRYPT);
        $karyawan->updatedAt = time();
        $this->karyawanRepository->updatePassword($karyawan);

        $_POST['nik'] = '2200000001';
        $_POST['password'] = '4377';

        $this->karyawanController->login();

        self::assertEquals(200, http_response_code());
    }
}
