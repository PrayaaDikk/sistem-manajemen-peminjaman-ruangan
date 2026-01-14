<?php

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        echo json_encode([
            'success' => true,
            'data' => $users
        ]);
    }

    public function show(int $id)
    {
        try {
            $user = $this->userService->getUserById($id);

            echo json_encode([
                'success' => true,
                'data' => $user
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
            $this->userService->createUser($data);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'User berhasil ditambahkan'
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
