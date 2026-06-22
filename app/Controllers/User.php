<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class User extends ResourceController
{
    protected $format = 'json';

    // ---------------------------------------------------------------
    // Helper: get + verify Bearer token from Authorization header
    // Returns the token string if valid, or sends an error response.
    // ---------------------------------------------------------------
    private function getValidToken(UserModel $userModel)
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (empty($authHeader) || !preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return null;
        }

        $token = $matches[1];
        $valid = $userModel->verifyToken($token);

        return $valid ? $token : null;
    }

    // ---------------------------------------------------------------
    // POST /user/registration
    // Calls: sp_check_email, sp_register
    // ---------------------------------------------------------------
    public function registration()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'first_name'      => 'required|min_length[2]',
            'last_name'       => 'required|min_length[2]',
            'email'           => 'required|valid_email',
            'phone_number'    => 'required|min_length[7]',
            'address'         => 'required|min_length[5]',
            'password'        => 'required|min_length[6]',
            'confirm_password'=> 'required|matches[password]',
            'terms_accepted'  => 'required',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        $userModel = new UserModel();

        $existing = $userModel->checkEmail($json['email']);

        if ($existing) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email already exists!',
            ], 409);
        }

        $result = $userModel->register(
            $json['first_name'],
            $json['last_name'],
            $json['email'],
            $json['phone_number'],
            $json['address'],
            $json['password'],
            (bool) $json['terms_accepted'],
            $json['dept_id'] ?? null,
            $json['branch_id'] ?? null
        );

        return $this->respond([
            'status'  => 'ok',
            'id'      => $result['id'],
            'message' => 'User successfully registered!',
        ], 200);
    }

    // ---------------------------------------------------------------
    // POST /user/login
    // Calls: sp_login, sp_save_token
    // ---------------------------------------------------------------
    public function login()
    {
        $json = $this->request->getJSON(true);

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

        $user = $userModel->login($json['email'], $json['password']);

        if (!$user) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid email or password!',
            ], 401);
        }

        $token = bin2hex(random_bytes(20));
        $userModel->saveToken($user['id'], $token);

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
    // Requires Bearer token. Calls: sp_get_users (with dept, branch, last_login)
    // ---------------------------------------------------------------
    public function index()
    {
        $userModel = new UserModel();
        $token = $this->getValidToken($userModel);

        if (!$token) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Token is required!',
            ], 401);
        }

        $users = $userModel->getAllUsers();

        return $this->respond([
            'status' => 'ok',
            'data'   => $users,
        ], 200);
    }

    // ---------------------------------------------------------------
    // GET /user/{id}
    // Requires Bearer token. Calls: sp_get_user_by_id
    // ---------------------------------------------------------------
    public function show($id = null)
    {
        $userModel = new UserModel();
        $token = $this->getValidToken($userModel);

        if (!$token) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Token is required!',
            ], 401);
        }

        $user = $userModel->getUserById($id);

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

    // ---------------------------------------------------------------
    // PUT /user/{id}
    // Requires Bearer token. Calls: sp_check_email, sp_update_user
    // ---------------------------------------------------------------
    public function update($id = null)
    {
        $userModel = new UserModel();
        $token = $this->getValidToken($userModel);

        if (!$token) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Token is required!',
            ], 401);
        }

        $json = $this->request->getJSON(true);

        $rules = [
            'first_name'   => 'required|min_length[2]',
            'last_name'    => 'required|min_length[2]',
            'email'        => 'required|valid_email',
            'address'      => 'required|min_length[5]',
            'phone_number' => 'required|min_length[7]',
            'status'       => 'required',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        // --- Check if user exists ---
        $existingUser = $userModel->getUserById($id);
        if (!$existingUser) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        // --- Check duplicate email (excluding current user's own email) ---
        $emailOwner = $userModel->checkEmail($json['email']);
        if ($emailOwner && (int) $emailOwner['id'] !== (int) $id) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email already exists!',
            ], 409);
        }

        $rowsAffected = $userModel->updateUser(
            (int) $id,
            $json['first_name'],
            $json['last_name'],
            $json['email'],
            $json['address'],
            $json['phone_number'],
            $json['dept_id'] ?? null,
            $json['branch_id'] ?? null,
            $json['status']
        );

        return $this->respond([
            'status'  => 'ok',
            'message' => 'User successfully updated!',
        ], 200);
    }

    // ---------------------------------------------------------------
    // POST /user/{id}/delete
    // Requires Bearer token. Calls: sp_delete_user
    // ---------------------------------------------------------------
    public function delete($id = null)
    {
        $userModel = new UserModel();
        $token = $this->getValidToken($userModel);

        if (!$token) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Token is required!',
            ], 401);
        }

        $existingUser = $userModel->getUserById($id);
        if (!$existingUser) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        $userModel->deleteUser((int) $id);

        return $this->respond([
            'status'  => 'ok',
            'message' => 'User successfully deleted!',
        ], 200);
    }
}