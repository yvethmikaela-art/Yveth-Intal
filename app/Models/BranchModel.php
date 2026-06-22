<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    public function getAllBranches(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_get_branches()');

        return $query->getResultArray();
    }
}