<?php

namespace RidwanHidayat\Absen\API\Service;

use Exception;
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
            throw new ValidationException('Parameter salah.');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK jangan kosong.');
        } else if ($request->nama == null || trim($request->nama) == '') {
            throw new ValidationException('Nama jangan kosong.');
        }
    }

    private function validationPasswordRequest(KaryawanRequest $request): void
    {
        if (!isset($request->nik) || !isset($request->password)) {
            throw new ValidationException('Parameter salah.');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK jangan kosong.');
        } else if ($request->password == null || trim($request->password) == '') {
            throw new ValidationException('Password jangan kosong.');
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

        return $karyawan;
    }

    public function findAll(): array
    {
        try {
            return $this->karyawanRepository->findAll();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findByNIK(string $nik): ?Karyawan
    {
        try {
            return $this->karyawanRepository->findByNIK($nik);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function save(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check != null) {
            throw new ValidationException('NIK sudah tersedia.');
        }

        $karyawan = $this->appendDomain($request);

        try {
            $result = $this->karyawanRepository->save($karyawan);

            $response = new KaryawanResponse();
            $response->karyawan = $result;

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function update(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('Karyawan tidak ditemukan.');
        }

        $karyawan = $this->appendDomain($request);

        try {
            $this->karyawanRepository->update($karyawan);

            $response = new KaryawanResponse();
            $response->karyawan = $karyawan;

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function delete(string $nik): int
    {
        try {
            return $this->karyawanRepository->deleteByNIK($nik);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updatePassword(KaryawanRequest $request): int
    {
        $this->validationPasswordRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('Karyawan tidak ditemukan.');
        }

        $karyawan = new Karyawan();
        $karyawan->nik = $request->nik;
        $karyawan->password = password_hash($request->password, PASSWORD_BCRYPT);

        try {
            return $this->karyawanRepository->updatePassword($karyawan);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function login(KaryawanRequest $request): ?KaryawanResponse
    {
        $result = $this->karyawanRepository->findByNIK($request->nik);

        if ($result == null) {
            throw new ValidationException('Username atau Password salah');
        }

        if (password_verify($request->password, $result->password)) {
            $response = new KaryawanResponse();
            $response->karyawan = $result;
            return $response;
        } else {
            throw new ValidationException('Username atau Password salah');
        }
    }
}
