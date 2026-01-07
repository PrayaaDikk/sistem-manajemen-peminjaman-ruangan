<?php
class BookingRepository {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function save($data) {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute($data);
    }
}