<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RidwanHidayat\Absen\API\App\Router;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Controller\KaryawanController;
use RidwanHidayat\Absen\API\Controller\AbsenController;
use RidwanHidayat\Absen\API\Middleware\AuthMiddleware;
use RidwanHidayat\Absen\API\Controller\KordinatController;

Database::getConnection('prod');

// mobile app
Router::add('POST', '/karyawan/login', KaryawanController::class, 'login', []);
Router::add('POST', '/karyawan', KaryawanController::class, 'save', [AuthMiddleware::class]);
Router::add('GET', '/karyawan/([0-9A-Z]*)', KaryawanController::class, 'findByNIK', [AuthMiddleware::class]);
Router::add('PATCH', '/karyawan/password', KaryawanController::class, 'updatePassword', [AuthMiddleware::class]);
Router::add('GET', '/absen/([0-9A-Z]*)', AbsenController::class, 'findByNIK', [AuthMiddleware::class]);
Router::add('POST', '/absen', AbsenController::class, 'save', [AuthMiddleware::class]);
Router::add('GET', '/kordinat', KordinatController::class, 'findKordinatAktif', [AuthMiddleware::class]);

// web admin
Router::add('GET', '/karyawan', KaryawanController::class, 'findAll', [AuthMiddleware::class]);
Router::add('PATCH', '/karyawan/face-point', KaryawanController::class, 'updateFacePoint', [AuthMiddleware::class]);
Router::add('DELETE', '/karyawan/([0-9A-Z]*)', KaryawanController::class, 'delete', [AuthMiddleware::class]);
Router::add('PATCH', '/karyawan/([0-9A-Z]*)', KaryawanController::class, 'update', [AuthMiddleware::class]);
Router::add('GET', '/absen', AbsenController::class, 'findAll', [AuthMiddleware::class]);

Router::run();
