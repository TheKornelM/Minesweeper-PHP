<?php

namespace Validators;

use Validators\ValidationResult;

class PasswordValidator
{
    public static function validate(string $password): ValidationResult
    {
        $errors = [];

        // Check if the password is at least 8 characters long
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Return the result object
        if (empty($errors)) {
            return new ValidationResult(true);
        } else {
            return new ValidationResult(false, $errors);
        }
    }
}