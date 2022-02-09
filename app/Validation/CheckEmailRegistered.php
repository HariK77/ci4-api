<?php

namespace App\Validation;

use App\Models\User;

class CheckEmailRegistered
{
    public function checkEmailRegistered(string $str, string $fields, array $data): bool
    {
        $model = new User();
        $count = $model->where('email', $data['email'])->countAllResults();
        return !$count ? false : true;
    }
}
