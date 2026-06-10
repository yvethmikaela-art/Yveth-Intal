<?php namespace App\Validation;

use App\Models\UserModel;

class UserRules
{
    /**
     * Validates a user by email and password.
     * Usage in rules: 'validateUser[email,password]'
     * CI passes the value of the field (password) as $str and all post data in $data.
     */
    public function validateUser(string $str, string $fields, array $data): bool
    {
        // $fields will be like 'email,password' but we only need the email key
        $emailField = explode(',', $fields)[0] ?? 'email';

        if (empty($data[$emailField])) {
            return false;
        }

        $model = new UserModel();
        $user = $model->where('email', $data[$emailField])->first();

        if (! $user) {
            return false;
        }

        return password_verify($str, $user['password']);
    }
}
