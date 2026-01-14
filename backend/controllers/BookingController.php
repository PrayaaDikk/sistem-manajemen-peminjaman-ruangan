<?php

use App\Exceptions\BookingException;

class BookingController
{
    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(?string $status = null): void
    {
        try {
            $bookings = $this->bookingService->getAllBookings($status);

            echo json_encode([
                'success' => true,
                'data' => $bookings
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(array $data)
    {
        try {
            $this->bookingService->createBooking($data);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Booking berhasil dibuat'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'code' => 'BOOKING_ERROR',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function pending()
    {
        echo json_encode([
            'success' => true,
            'data' => $this->bookingService->getPendingBookings()
        ]);
    }

    public function approve(int $id)
    {
        try {
            $this->bookingService->approve($id);
            echo json_encode([
                'success' => true,
                'message' => 'Booking disetujui'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject(int $id)
    {
        try {
            $this->bookingService->reject($id);
            echo json_encode([
                'success' => true,
                'message' => 'Booking ditolak'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function myBookings(int $userId, ?string $status = null): void
    {
        try {
            $data = $this->bookingService->getBookingsByUser(
                $userId,
                $status
            );

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function history(int $userId)
    {
        try {
            $data = $this->bookingService->getBookingHistory($userId);

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
