<?php

namespace Validators;

use Validators\Result;

class PasswordValidator
{
    public static function validate(string $password): Result
    {
        $errors = [];

        // Check if the password is at least 8 characters long
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Return the result object
        if (empty($errors)) {
            return new Result(true);
        } else {
            return new Result(false, $errors);
        }
    }
}