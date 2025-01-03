<?php

namespace Validators;

include "../../src/Validators/UsernameValidator.php";
include "../../src/Validators/PasswordValidator.php";
include "../../src/Validators/EmailValidator.php";

use Validators\ValidationResult;

class UserDataValidator
{
    public static function validate(string $username, string $email, string $password): ValidationResult
    {
        $errors = [];

        // Validate username
        $usernameResult = UsernameValidator::validate($username);
        if (!$usernameResult->isValid) {
            $errors = array_merge($errors, $usernameResult->errors);
        }

        // Validate email
        $emailResult = EmailValidator::validate($email);
        if (!$emailResult->isValid) {
            $errors = array_merge($errors, $emailResult->errors);
        }

        // Validate password
        $passwordResult = PasswordValidator::validate($password);
        if (!$passwordResult->isValid) {
            $errors = array_merge($errors, $passwordResult->errors);
        }

        // Return the combined result object
        if (empty($errors)) {
            return new ValidationResult(true);
        } else {
            return new ValidationResult(false, $errors);
        }
    }
}