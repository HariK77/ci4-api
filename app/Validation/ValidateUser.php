<?php

namespace App\Validation;

use App\Models\User;
use Exception;

class ValidateUser
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new User();
            $user = $model->findByEmail($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }
}