<?php

class RoomController
{
    private RoomService $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index()
    {
        $rooms = $this->roomService->getAllRooms();

        echo json_encode([
            'success' => true,
            'data' => $rooms
        ]);
    }

    public function show(int $id)
    {
        try {
            $room = $this->roomService->getRoomById($id);

            echo json_encode([
                'success' => true,
                'data' => $room
            ]);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(array $data)
    {
        try {
            $this->roomService->createRoom($data);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan'
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
