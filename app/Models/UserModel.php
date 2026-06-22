<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // ---------------------------------------------------------------
    // sp_check_email — check if email already exists
    // ---------------------------------------------------------------
    public function checkEmail(string $email): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query('CALL sp_check_email(?)', [$email]);
        $result = $query->getRowArray();

        return $result ?: null;
    }

    // ---------------------------------------------------------------
    // sp_register — insert new user (JSON parameter)
    // ---------------------------------------------------------------
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $phoneNumber,
        string $address,
        string $password,
        bool $termsAccepted,
        ?int $deptId = null,
        ?int $branchId = null
    ): array {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $jsonData = json_encode([
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'email'          => $email,
            'phone_number'   => $phoneNumber,
            'address'        => $address,
            'password'       => $hashedPassword,
            'terms_accepted' => $termsAccepted ? 1 : 0,
            'dept_id'        => $deptId,
            'branch_id'      => $branchId,
        ]);

        $db     = \Config\Database::connect();
        $query  = $db->query('CALL sp_register(?)', [$jsonData]);
        $result = $query->getRowArray();

        return $result ?? ['id' => 0];
    }

    // ---------------------------------------------------------------
    // sp_login — verify email and password
    // ---------------------------------------------------------------
    public function login(string $email, string $password): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query('CALL sp_login(?)', [$email]);
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        if (!password_verify($password, $result['password'])) {
            return null;
        }

        return $result;
    }

    // ---------------------------------------------------------------
    // sp_get_users — get all users with Department, Branch, Last Login
    // ---------------------------------------------------------------
    public function getAllUsers(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_get_users()');

        return $query->getResultArray();
    }

    // ---------------------------------------------------------------
    // sp_get_user_by_id — get specific user
    // ---------------------------------------------------------------
    public function getUserById(int $id): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query('CALL sp_get_user_by_id(?)', [$id]);
        $result = $query->getRowArray();

        return $result ?: null;
    }

    // ---------------------------------------------------------------
    // sp_update_user — update existing user
    // ---------------------------------------------------------------
    public function updateUser(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $address,
        string $phoneNumber,
        ?int $deptId,
        ?int $branchId,
        string $status
    ): int {
        $db = \Config\Database::connect();
        $query = $db->query(
            'CALL sp_update_user(?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$id, $firstName, $lastName, $email, $address, $phoneNumber, $deptId, $branchId, $status]
        );
        $result = $query->getRowArray();

        return (int) ($result['rows_affected'] ?? 0);
    }

    // ---------------------------------------------------------------
    // sp_delete_user — delete user
    // ---------------------------------------------------------------
    public function deleteUser(int $id): int
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_delete_user(?)', [$id]);
        $result = $query->getRowArray();

        return (int) ($result['rows_affected'] ?? 0);
    }

    // ---------------------------------------------------------------
    // sp_save_token — store login token
    // ---------------------------------------------------------------
    public function saveToken(int $userId, string $token): void
    {
        $db = \Config\Database::connect();
        $db->query('CALL sp_save_token(?, ?)', [$userId, $token]);
    }

    // ---------------------------------------------------------------
    // sp_verify_token — check if token is valid, return user_id
    // ---------------------------------------------------------------
    public function verifyToken(string $token): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query('CALL sp_verify_token(?)', [$token]);
        $result = $query->getRowArray();

        return $result ?: null;
    }
}