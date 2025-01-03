<?php

namespace Validators;

use Validators\ValidationResult;

class UsernameValidator
{
    public static function validate(string $username): ValidationResult
    {
        $errors = [];

        // Check if the username is empty
        if (empty($username)) {
            $errors[] = "Username cannot be empty.";
        }

        // Check if the username contains only letters, numbers, and underscores
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Username can only contain letters, numbers, and underscores.";
        }

        // Check if the username is a maximum of 45 characters long
        if (strlen($username) > 45) {
            $errors[] = "Username cannot be longer than 45 characters.";
        }

        // Return the result object
        if (empty($errors)) {
            return new ValidationResult(true);
        } else {
            return new ValidationResult(false, $errors);
        }
    }
}