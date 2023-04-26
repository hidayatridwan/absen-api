<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RidwanHidayat\Absen\API\App\Router;
use RidwanHidayat\Absen\API\Config\Database;
use RidwanHidayat\Absen\API\Controller\KaryawanController;
use RidwanHidayat\Absen\API\Controller\AbsenController;
use RidwanHidayat\Absen\API\Middleware\AuthMiddleware;
use RidwanHidayat\Absen\API\Controller\KordinatController;

Database::getConnection('prod');

Router::add('GET', '/karyawan', KaryawanController::class, 'findAll', [AuthMiddleware::class]);
Router::add('GET', '/karyawan/([0-9]*)', KaryawanController::class, 'findByNIK', [AuthMiddleware::class]);
Router::add('POST', '/karyawan', KaryawanController::class, 'save', [AuthMiddleware::class]);
Router::add('PUT', '/karyawan', KaryawanController::class, 'update', [AuthMiddleware::class]);
Router::add('DELETE', '/karyawan', KaryawanController::class, 'delete', [AuthMiddleware::class]);
Router::add('PUT', '/karyawan/password', KaryawanController::class, 'updatePassword', [AuthMiddleware::class]);
Router::add('POST', '/karyawan/login', KaryawanController::class, 'login', []);
Router::add('PUT', '/karyawan/face-point', KaryawanController::class, 'updateFacePoint', []);

Router::add('GET', '/absen', AbsenController::class, 'findAll', [AuthMiddleware::class]);
Router::add('GET', '/absen/([0-9]*)', AbsenController::class, 'findByNIK', [AuthMiddleware::class]);
Router::add('POST', '/absen', AbsenController::class, 'save', [AuthMiddleware::class]);

Router::add('GET', '/kordinat', KordinatController::class, 'findKordinatAktif', [AuthMiddleware::class]);

Router::run();