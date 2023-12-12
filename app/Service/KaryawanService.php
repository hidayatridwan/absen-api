<?php

namespace RidwanHidayat\Absen\API\Service;

use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Domain\Karyawan;
use RidwanHidayat\Absen\API\Exception\ValidationException;
use RidwanHidayat\Absen\API\Model\KaryawanRequest;
use RidwanHidayat\Absen\API\Model\KaryawanResponse;
use RidwanHidayat\Absen\API\Repository\KaryawanRepository;

class KaryawanService
{
    private KaryawanRepository $karyawanRepository;

    public function __construct(KaryawanRepository $karyawanRepository)
    {
        $this->karyawanRepository = $karyawanRepository;
    }

    private function validationKaryawanRequest(KaryawanRequest $request): void
    {
        if (!isset($request->nik) || !isset($request->nama)) {
            throw new ValidationException('Invalid parameters');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK do not blank');
        } else if ($request->nama == null || trim($request->nama) == '') {
            throw new ValidationException('Nama do not blank');
        }
    }

    private function validationPasswordRequest(KaryawanRequest $request): void
    {
        if (!isset($request->nik) || !isset($request->password)) {
            throw new ValidationException('Invalid parameters');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK do not blank');
        } else if ($request->password == null || trim($request->password) == '') {
            throw new ValidationException('Password do not blank');
        }
    }

    protected function appendDomain(KaryawanRequest $request): Karyawan
    {
        $karyawan = new Karyawan();
        $karyawan->nik = $request->nik;
        $karyawan->nama = $request->nama;
        $karyawan->tanggalLahir = $request->tanggalLahir;
        $karyawan->jenisKelamin = $request->jenisKelamin;
        $karyawan->tempatLahir = $request->tempatLahir;
        $karyawan->noHp = $request->noHp;
        $karyawan->alamat = $request->alamat;
        $karyawan->email = $request->email;
        $karyawan->divisi = $request->divisi;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->facePoint = $request->facePoint;

        return $karyawan;
    }

    public function findAll(): array
    {
        return $this->karyawanRepository->findAll();
    }

    public function findByNIK(string $nik): ?Karyawan
    {
        return $this->karyawanRepository->findByNIK($nik);
    }

    public function save(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check != null) {
            throw new ValidationException('NIK already exist!');
        }

        $karyawan = $this->appendDomain($request);

        $result = $this->karyawanRepository->save($karyawan);

        $response = new KaryawanResponse();
        $response->karyawan = $result;

        return $response;
    }

    public function update(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('NIK not found!');
        }

        $karyawan = $this->appendDomain($request);

        $this->karyawanRepository->update($karyawan);

        $response = new KaryawanResponse();
        $response->karyawan = $karyawan;

        return $response;
    }

    public function delete(string $nik): int
    {
        return $this->karyawanRepository->deleteByNIK($nik);
    }

    public function updatePassword(KaryawanRequest $request): int
    {
        $this->validationPasswordRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('NIK not found!');
        }

        if (!password_verify($request->oldPassword, $check->password)) {
            throw new ValidationException('Old password is not valid');
        }

        $karyawan = new Karyawan();
        $karyawan->nik = $request->nik;
        $karyawan->password = password_hash($request->password, PASSWORD_BCRYPT);

        return $this->karyawanRepository->updatePassword($karyawan);
    }

    public function login(KaryawanRequest $request): ?KaryawanResponse
    {
        Database::beginTransaction();
        $result = $this->karyawanRepository->findByNIK($request->nik);

        if ($result == null) {
            Database::rollBackTransaction();
            throw new ValidationException('Invalid username or password');
        }

        if (password_verify($request->password, $result->password)) {
            $response = new KaryawanResponse();
            $response->karyawan = $result;

            Database::commitTransaction();

            return $response;
        } else {
            Database::rollBackTransaction();
            throw new ValidationException('Invalid username or password');
        }
    }

    public function updateFacePoint(KaryawanRequest $request): int
    {
        $karyawan = new Karyawan();
        $karyawan->nik = $request->nik;
        $karyawan->facePoint = $request->facePoint;

        return $this->karyawanRepository->updateFacePoint($karyawan);
    }
}
