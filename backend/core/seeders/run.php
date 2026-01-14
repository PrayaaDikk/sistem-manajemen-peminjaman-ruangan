<?php

require_once '../../core/Database.php';
require_once 'UserSeeder.php';
require_once 'RoomSeeder.php';

echo "Menjalankan database seeder...\n";

$db = (new Database())->getConnection();

$userSeeder = new UserSeeder($db);
$userSeeder->run();

$roomSeeder = new RoomSeeder($db);
$roomSeeder->run();

echo "Semua seeder berhasil dijalankan\n";
