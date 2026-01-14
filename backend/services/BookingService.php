<?php
require_once __DIR__ . '/../core/Config.php';

use App\Exceptions\BookingException;

class BookingService
{
    private BookingRepository $bookingRepository;
    private UserRepository $userRepository;
    private RoomRepository $roomRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        UserRepository $userRepository,
        RoomRepository $roomRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
    }

    public function getAllBookings(?string $status = null): array
    {
        if ($status) {
            return $this->bookingRepository->findAllByStatus($status);
        }

        return $this->bookingRepository->findAll();
    }


    public function createBooking(array $data): void
    {
        foreach (['user_id', 'room_id', 'booking_date', 'start_time', 'end_time'] as $field) {
            if (empty($data[$field])) {
                throw new BookingException(
                    "Field $field wajib diisi",
                    "VALIDATION_ERROR"
                );
            }
        }

        $today = date('Y-m-d');
        $now   = date('H:i');

        if ($data['booking_date'] < $today) {
            throw new BookingException(
                'Tanggal booking tidak boleh hari yang sudah lewat',
                'INVALID_DATE'
            );
        }

        if ($data['booking_date'] === $today && $data['start_time'] <= $now) {
            throw new BookingException(
                'Jam booking tidak boleh lebih kecil dari waktu sekarang',
                'INVALID_TIME'
            );
        }

        if (!$this->userRepository->exists($data['user_id'])) {
            throw new BookingException(
                'User tidak ditemukan',
                'USER_NOT_FOUND'
            );
        }

        if (!$this->roomRepository->exists($data['room_id'])) {
            throw new BookingException(
                'Ruangan tidak ditemukan',
                'ROOM_NOT_FOUND'
            );
        }

        if (!$this->roomRepository->isAvailable($data['room_id'])) {
            throw new BookingException(
                'Ruangan sedang tidak tersedia',
                'ROOM_UNAVAILABLE'
            );
        }

        $maxBooking = Config::getMaxBookingPerDay();

        $totalBookingHariIni =
            $this->bookingRepository->countUserBookingByDate(
                $data['user_id'],
                $data['booking_date']
            );

        if ($totalBookingHariIni >= $maxBooking) {
            throw new BookingException(
                "Limit booking per hari tercapai (maksimal $maxBooking)",
                'MAX_BOOKING'
            );
        }

        if ($this->bookingRepository->hasTimeConflict(
            $data['room_id'],
            $data['booking_date'],
            $data['start_time'],
            $data['end_time']
        )) {
            throw new BookingException(
                'Jam booking bentrok dengan booking lain pada ruangan ini',
                'TIME_CONFLICT'
            );
        }

        $this->bookingRepository->save([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'booking_date' => $data['booking_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 'pending'
        ]);
    }

    public function getPendingBookings(): array
    {
        return $this->bookingRepository->findPending();
    }

    public function approve(int $bookingId): void
    {
        $booking = $this->bookingRepository->findById($bookingId);

        if (!$booking) {
            throw new Exception("Booking tidak ditemukan");
        }

        if ($booking['status'] !== 'pending') {
            throw new Exception("Booking sudah diproses");
        }

        $this->bookingRepository->updateStatus(
            $bookingId,
            'approved'
        );

        $this->roomRepository->updateStatus(
            $booking['room_id'],
            'booked'
        );
    }

    public function reject(int $bookingId): void
    {
        $booking = $this->bookingRepository->findById($bookingId);

        if (!$booking) {
            throw new Exception("Booking tidak ditemukan");
        }

        if ($booking['status'] !== 'pending') {
            throw new Exception("Booking sudah diproses");
        }

        $this->bookingRepository->updateStatus($bookingId, 'rejected');
        $this->roomRepository->updateStatus(
            $booking['room_id'],
            'available'
        );
    }

    public function autoReleaseRooms(): void
    {
        $expiredBookings =
            $this->bookingRepository->getExpiredBookings();

        foreach ($expiredBookings as $booking) {
            $this->bookingRepository->updateStatus(
                $booking['id'],
                'completed'
            );

            $this->roomRepository->updateStatus(
                $booking['room_id'],
                'available'
            );
        }
    }

    public function getBookingsByUser(
        int $userId,
        ?string $status = null
    ): array {
        if (!$this->userRepository->exists($userId)) {
            throw new Exception('User tidak ditemukan');
        }

        return $this->bookingRepository->getByUserId(
            $userId,
            $status
        );
    }

    public function getBookingHistory(int $userId): array
    {
        if (!$this->userRepository->exists($userId)) {
            throw new Exception('User tidak ditemukan');
        }

        return $this->bookingRepository->getHistoryByUser($userId);
    }
}
