<?php

class RoomRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT id, room_name, capacity, location, status
             FROM rooms"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, room_name, capacity, location, status
             FROM rooms WHERE id = ?"
        );
        $stmt->execute([$id]);

        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        return $room ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM rooms WHERE id = ?"
        );
        $stmt->execute([$id]);

        return $stmt->fetch() !== false;
    }

    public function isAvailable(int $id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM rooms
                WHERE id = ? AND status = 'available'"
        );
        $stmt->execute([$id]);

        return $stmt->fetch() !== false;
    }

    public function updateStatus(int $roomId, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE rooms SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $roomId]);
    }

    public function save(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rooms (room_name, capacity, location, status)
                VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['room_name'],
            $data['capacity'],
            $data['location'],
            $data['status']
        ]);
    }
}
