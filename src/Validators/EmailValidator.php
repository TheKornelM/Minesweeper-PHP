<?php

namespace Validators;

use Validators\Result;

define("MAX_EMAIL_LENGTH", 128);

class EmailValidator
{
    public static function validate(string $email): Result
    {
        $errors = [];

        // Check if the email is empty
        if (empty($email)) {
            $errors[] = "Email cannot be empty.";
        }

        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Check email length
        if (strlen($email) > MAX_EMAIL_LENGTH) {
            $errors[] = "Email cannot be longer than " . MAX_EMAIL_LENGTH . " characters.";
        }

        // Return the result object
        if (empty($errors)) {
            return new Result(true);
        } else {
            return new Result(false, $errors);
        }
    }
}