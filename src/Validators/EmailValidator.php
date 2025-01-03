<?php

namespace Validators;

use Validators\ValidationResult;

define("MAX_EMAIL_LENGTH", 128);

class EmailValidator
{
    public static function validate(string $email): ValidationResult
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
            return new ValidationResult(true);
        } else {
            return new ValidationResult(false, $errors);
        }
    }
}