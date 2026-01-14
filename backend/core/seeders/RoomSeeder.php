<?php

class RoomSeeder
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $locations = ['Lantai 1', 'Lantai 2', 'Lantai 3'];

        $stmt = $this->db->prepare(
            "INSERT INTO rooms (room_name, capacity, location)
            VALUES (?, ?, ?)"
        );

        for ($i = 0; $i < 10; $i++) {
            $roomName = 'Room ' . ($i + 1);

            $capacity = rand(10, 50);

            $location = $locations[array_rand($locations)];

            $stmt->execute([
                $roomName,
                $capacity,
                $location
            ]);
        }

        echo "Seeder rooms berhasil dijalankan\n";
    }
}
