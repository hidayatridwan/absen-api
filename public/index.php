<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RidwanHidayat\Absen\API\App\Router;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Controller\KaryawanController;
use RidwanHidayat\Absen\API\Controller\AbsenController;

Database::getConnection('prod');

Router::add('GET', '/karyawan', KaryawanController::class, 'findAll');
Router::add('GET', '/karyawan/([0-9]*)', KaryawanController::class, 'findByNIK');
Router::add('POST', '/karyawan', KaryawanController::class, 'save');
Router::add('PUT', '/karyawan', KaryawanController::class, 'update');
Router::add('DELETE', '/karyawan', KaryawanController::class, 'delete');
Router::add('PUT', '/karyawan/password', KaryawanController::class, 'updatePassword');
Router::add('POST', '/karyawan/login', KaryawanController::class, 'login');
Router::add('PUT', '/karyawan/face-point', KaryawanController::class, 'updateFacePoint');

Router::add('GET', '/absen', AbsenController::class, 'findAll');
Router::add('GET', '/absen/([0-9]*)', AbsenController::class, 'findByNIK');
Router::add('POST', '/absen', AbsenController::class, 'save');
Router::add('PUT', '/absen', AbsenController::class, 'save');
Router::add('DELETE', '/absen', AbsenController::class, 'save');

Router::run();