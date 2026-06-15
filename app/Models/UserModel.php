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
    // sp_register — insert new user
    // ---------------------------------------------------------------
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): array {
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $db     = \Config\Database::connect();
        $query  = $db->query(
            'CALL sp_register(?, ?, ?, ?)',
            [$firstName, $lastName, $email, $hashedPassword]
        );
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

        // Verify hashed password
        if (!password_verify($password, $result['password'])) {
            return null;
        }

        return $result;
    }

    // ---------------------------------------------------------------
    // Get all users
    // ---------------------------------------------------------------
    public function getAllUsers(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('SELECT id, firstname, lastname, email, created_at FROM users ORDER BY id ASC');

        return $query->getResultArray();
    }

    // ---------------------------------------------------------------
    // Get specific user by ID
    // ---------------------------------------------------------------
    public function getUserById(int $id): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query(
            'SELECT id, firstname, lastname, email, created_at FROM users WHERE id = ?',
            [$id]
        );
        $result = $query->getRowArray();

        return $result ?: null;
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