<?php

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserById(int $id): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new Exception("User tidak ditemukan");
        }

        return $user;
    }

    public function createUser(array $data): void
    {
        if (empty($data['name']) || empty($data['email'])) {
            throw new Exception("Nama dan email wajib diisi");
        }

        if (!isset($data['role'])) {
            $data['role'] = 'student';
        }

        $this->userRepository->save($data);
    }
}
