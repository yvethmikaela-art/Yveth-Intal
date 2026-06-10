<?php

namespace App\Libraries;

use OAuth2\Storage\Pdo;

class MyOAuthStorage extends Pdo
{
    public function getUser($username)
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')
                   ->where('email', $username)
                   ->get()
                   ->getRowArray();

        if (!$user) {
            return false;
        }

        return array_merge($user, [
            'user_id' => $user['email']
        ]);
    }

    public function checkUserCredentials($username, $password)
    {
        $user = $this->getUser($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
}
