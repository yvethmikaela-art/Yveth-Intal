<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BranchModel;
use App\Models\UserModel;

class Branch extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $userModel = new UserModel();
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (empty($authHeader) || !preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Token is required!',
            ], 401);
        }

        $valid = $userModel->verifyToken($matches[1]);
        if (!$valid) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid or expired token!',
            ], 401);
        }

        $branchModel = new BranchModel();
        $branches    = $branchModel->getAllBranches();

        return $this->respond([
            'status' => 'ok',
            'data'   => $branches,
        ], 200);
    }
}