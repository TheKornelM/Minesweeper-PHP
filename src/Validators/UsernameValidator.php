<?php

namespace Validators;

use Validators\Result;

define("MAX_USERNAME_LENGTH", 128);

class UsernameValidator
{
    public static function validate(string $username): Result
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

        // Check username length
        if (strlen($username) > MAX_USERNAME_LENGTH) {
            $errors[] = "Username cannot be longer than " . MAX_USERNAME_LENGTH . " characters.";
        }

        // Return the result object
        if (empty($errors)) {
            return new Result(true);
        } else {
            return new Result(false, $errors);
        }
    }
}