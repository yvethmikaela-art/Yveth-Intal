<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class User extends ResourceController
{
    protected $format = 'json';

    // ---------------------------------------------------------------
    // POST /user/registration
    // Calls: sp_register
    // ---------------------------------------------------------------
    public function registration()
    {
        $json = $this->request->getJSON(true);

        // --- Input validation ---
        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name'  => 'required|min_length[2]',
            'email'      => 'required|valid_email',
            'password'   => 'required|min_length[6]',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        $userModel = new UserModel();

        // --- Step 1: Check if email already exists via sp_check_email ---
        $existing = $userModel->checkEmail($json['email']);

        if ($existing) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email already exists!',
            ], 409);
        }

        // --- Step 2: Register user via sp_register ---
        $result = $userModel->register(
            $json['first_name'],
            $json['last_name'],
            $json['email'],
            $json['password']
        );

        return $this->respond([
            'status'  => 'ok',
            'id'      => $result['id'],
            'message' => 'User successfully registered!',
        ], 200);
    }

    // ---------------------------------------------------------------
    // POST /user/login
    // Calls: sp_login
    // ---------------------------------------------------------------
    public function login()
    {
        $json = $this->request->getJSON(true);

        // --- Input validation ---
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        $userModel = new UserModel();

        // --- Step 1: Verify user via sp_login ---
        $user = $userModel->login($json['email'], $json['password']);

        if (!$user) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid email or password!',
            ], 401);
        }

        // --- Step 2: Generate simple token ---
        $token = bin2hex(random_bytes(20));

        return $this->respond([
            'status'       => 'ok',
            'message'      => 'Login successful!',
            'access_token' => $token,
            'user'         => [
                'id'         => $user['id'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
            ],
        ], 200);
    }

    // ---------------------------------------------------------------
    // GET /user
    // Returns all users
    // ---------------------------------------------------------------
    public function index()
    {
        $userModel = new UserModel();
        $users     = $userModel->getAllUsers();

        return $this->respond([
            'status' => 'ok',
            'data'   => $users,
        ], 200);
    }

    // ---------------------------------------------------------------
    // GET /user/{id}
    // Returns specific user
    // ---------------------------------------------------------------
    public function show($id = null)
    {
        $userModel = new UserModel();
        $user      = $userModel->getUserById($id);

        if (!$user) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        return $this->respond([
            'status' => 'ok',
            'data'   => $user,
        ], 200);
    }
}