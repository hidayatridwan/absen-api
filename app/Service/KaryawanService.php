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

    /**
     * @throws ValidationException
     */
    private function validationKaryawanRequest(KaryawanRequest $request): void
    {
        if (!isset($request->nik) || !isset($request->nama)) {
            throw new ValidationException('Invalid parameters!');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK do not blank!');
        } else if ($request->nama == null || trim($request->nama) == '') {
            throw new ValidationException('Nama do not blank!');
        }
    }

    /**
     * @throws ValidationException
     */
    private function validationPasswordRequest(KaryawanRequest $request): void
    {
        if (!isset($request->nik) || !isset($request->password)) {
            throw new ValidationException('Invalid parameters!');
        } else if ($request->nik == null || trim($request->nik) == '') {
            throw new ValidationException('NIK do not blank!');
        } else if ($request->password == null || trim($request->password) == '') {
            throw new ValidationException('Password do not blank!');
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

    /**
     * @throws ValidationException
     */
    public function save(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check != null) {
            throw new ValidationException('NIK already exist!');
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

    /**
     * @throws ValidationException
     */
    public function update(KaryawanRequest $request): KaryawanResponse
    {
        $this->validationKaryawanRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('Karyawan not found!');
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

    /**
     * @throws ValidationException
     */
    public function updatePassword(KaryawanRequest $request): int
    {
        $this->validationPasswordRequest($request);

        $check = $this->karyawanRepository->findByNIK($request->nik);
        if ($check == null) {
            throw new ValidationException('Karyawan not found!');
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

    /**
     * @throws ValidationException
     */
    public function login(KaryawanRequest $request): ?KaryawanResponse
    {
        $result = $this->karyawanRepository->findByNIK($request->nik);

        if ($result == null) {
            throw new ValidationException('Invalid Username or Password!');
        }

        if (password_verify($request->password, $result->password)) {
            $response = new KaryawanResponse();
            $response->karyawan = $result;
//            update token
            $karyawan = new Karyawan();
            $karyawan->nik = $request->nik;
            $karyawan->token = md5(date('YmdHis'));
            $this->karyawanRepository->updateToken($karyawan);
            return $response;
        } else {
            throw new ValidationException('Invalid Username or Password!');
        }
    }

    public function updateFacePoint(KaryawanRequest $request): int
    {
        $karyawan = new Karyawan();
        $karyawan->nik = $request->nik;
        $karyawan->facePoint = $request->facePoint;

        try {
            return $this->karyawanRepository->updateFacePoint($karyawan);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
