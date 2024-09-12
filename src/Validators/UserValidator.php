<?php

namespace App\Validators;

use App\Kernel\Validator\Validator;

class UserValidator extends Validator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['first_name'])) {
            $errors[] = 'First name is required';
        }

        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required';
        }

        if (!in_array($data['status'], [0, 1])) {
            $errors[] = 'Invalid status value';
        }

        if (!in_array($data['role'], ['admin', 'user'])) {
            $errors[] = 'You need to select role';
        }

        return $errors;
    }
}