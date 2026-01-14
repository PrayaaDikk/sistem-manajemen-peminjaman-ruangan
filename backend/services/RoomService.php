<?php

class RoomService
{
    private RoomRepository $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function getAllRooms(): array
    {
        return $this->roomRepository->findAll();
    }

    public function getRoomById(int $id): array
    {
        $room = $this->roomRepository->findById($id);

        if (!$room) {
            throw new Exception("Ruangan tidak ditemukan");
        }

        return $room;
    }

    public function createRoom(array $data): void
    {
        if (empty($data['room_name']) || empty($data['capacity'])) {
            throw new Exception("Nama ruangan dan kapasitas wajib diisi");
        }

        if (!isset($data['status'])) {
            $data['status'] = 'available';
        }

        $this->roomRepository->save($data);
    }
}
