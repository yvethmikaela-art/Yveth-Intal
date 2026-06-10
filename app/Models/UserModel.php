<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // ---------------------------------------------------------------
    // Call Stored Procedure: sp_get_all_users
    // Returns all users
    // ---------------------------------------------------------------
    public function getAllUsers(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_get_all_users()');

        return $query->getResultArray();
    }

    // ---------------------------------------------------------------
    // Call Stored Procedure: sp_get_user_by_id
    // Returns specific user by ID
    // ---------------------------------------------------------------
    public function getUserById(int $id): array|null
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_get_user_by_id(?)', [$id]);
        $user  = $query->getRowArray();

        return $user ?: null;
    }
}