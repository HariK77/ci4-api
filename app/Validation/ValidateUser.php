<?php

namespace App\Validation;

use App\Models\User;

class ValidateUser
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        $model = new User();
        $user = $model->where('email', $data['email'])->first();
        return password_verify($data['password'], $user->password);
    }
}