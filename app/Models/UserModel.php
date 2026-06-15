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
        $db     = \Config\Database::connect();
        $query  = $db->query(
            'CALL sp_register(?, ?, ?, ?)',
            [$firstName, $lastName, $email, $password]
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
        $query  = $db->query('CALL sp_login(?, ?)', [$email, $password]);
        $result = $query->getRowArray();

        return $result ?: null;
    }

    // ---------------------------------------------------------------
    // Get all users
    // ---------------------------------------------------------------
    public function getAllUsers(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('SELECT id, first_name, last_name, email, created_at FROM users ORDER BY id ASC');

        return $query->getResultArray();
    }

    // ---------------------------------------------------------------
    // Get specific user by ID
    // ---------------------------------------------------------------
    public function getUserById(int $id): array|null
    {
        $db     = \Config\Database::connect();
        $query  = $db->query(
            'SELECT id, first_name, last_name, email, created_at FROM users WHERE id = ?',
            [$id]
        );
        $result = $query->getRowArray();

        return $result ?: null;
    }
}

