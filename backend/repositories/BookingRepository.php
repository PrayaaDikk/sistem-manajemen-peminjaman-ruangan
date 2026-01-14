<?php

class BookingRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function isRoomBooked(int $roomId, string $date): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM bookings
            WHERE room_id = ? AND booking_date = ?"
        );
        $stmt->execute([$roomId, $date]);

        return $stmt->fetch() !== false;
    }

    public function findPending(): array
    {
        return $this->db
            ->query("SELECT * FROM bookings WHERE status = 'pending'")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $bookingId, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET status = :status WHERE id = :id"
        );
        $stmt->execute([
            'status' => $status,
            'id' => $bookingId
        ]);
    }

    public function countUserBookingByDate($userId, $date)
    {
        $sql = "SELECT COUNT(*) 
            FROM bookings 
            WHERE user_id = :user_id 
            AND booking_date = :booking_date
            AND status IN ('pending', 'approved')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'booking_date' => $date
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function hasTimeConflict(
        int $roomId,
        string $date,
        string $startTime,
        string $endTime
    ): bool {
        $sql = "SELECT COUNT(*) 
            FROM bookings
            WHERE room_id = :room_id
                AND booking_date = :booking_date
                AND status IN ('pending', 'approved')
                AND start_time < :end_time
                AND end_time > :start_time";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'room_id' => $roomId,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getExpiredBookings(): array
    {
        $sql = "
        SELECT * FROM bookings
        WHERE status = 'approved'
        AND (
            booking_date < CURDATE()
            OR (booking_date = CURDATE() AND end_time < CURTIME())
        )
    ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId(int $userId, ?string $status = null): array
    {
        $sql = "
        SELECT 
            b.id,
            b.booking_date,
            b.start_time,
            b.end_time,
            b.status,
            r.room_name AS room_name,
            r.location
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = :user_id
    ";

        if ($status) {
            $sql .= " AND b.status = :status";
        }

        $sql .= " ORDER BY b.booking_date DESC, b.start_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);

        if ($status) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
        SELECT b.*, r.name AS room_name
        FROM bookings b
        JOIN rooms r ON r.id = b.room_id
        WHERE b.user_id = :user_id
            AND (
                b.status IN ('completed', 'rejected')
                OR b.booking_date < CURDATE()
            )
        ORDER BY b.booking_date DESC
    ");

        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO bookings 
            (user_id, room_id, booking_date, start_time, end_time, status)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['user_id'],
            $data['room_id'],
            $data['booking_date'],
            $data['start_time'],
            $data['end_time'],
            'pending'
        ]);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT 
                b.id,
                u.name AS user_name,
                r.room_name,
                b.booking_date,
                b.status
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                ORDER BY b.booking_date DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM bookings WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findAllByStatus(string $status): array
    {
        $sql = "
        SELECT 
            b.id,
            u.name AS user_name,
            r.room_name,
            b.booking_date,
            b.start_time,
            b.end_time,
            b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        WHERE b.status = :status
        ORDER BY b.booking_date DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
