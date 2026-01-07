<?php
require_once '../core/Database.php';
require_once '../repositories/BookingRepository.php';
require_once '../services/BookingService.php';
require_once '../controllers/BookingController.php';

$db = (new Database())->connect();
$repo = new BookingRepository($db);
$service = new BookingService($repo);
$controller = new BookingController($service);

$controller->store([1, 1, '2026-01-10', '08:00', '10:00']);