<?php

class UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT id, name, email, role, created_at FROM users"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, role FROM users WHERE id = ?"
        );
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users WHERE id = ?"
        );
        $stmt->execute([$id]);

        return $stmt->fetch() !== false;
    }

    public function save(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, role, created_at)
            VALUES (?, ?, ?, NOW())"
        );

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role']
        ]);
    }
}
