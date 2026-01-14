<?php

class UserSeeder
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $users = [
            ['Admin Sistem', 'admin@system.com', 'admin'],
            ['Budi Santoso', 'budi@mail.com', 'user'],
            ['Siti Aminah', 'siti@mail.com', 'user'],
            ['Andi Wijaya', 'andi@mail.com', 'user'],
            ['Rina Kusuma', 'rina@mail.com', 'user']
        ];

        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, role) VALUES (?, ?, ?)"
        );

        foreach ($users as $user) {
            $stmt->execute($user);
        }

        echo "Seeder users berhasil dijalankan\n";
    }
}
