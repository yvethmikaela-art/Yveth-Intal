<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    public function getAllDepartments(): array
    {
        $db    = \Config\Database::connect();
        $query = $db->query('CALL sp_get_departments()');

        return $query->getResultArray();
    }
}