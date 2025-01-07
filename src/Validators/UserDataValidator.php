<?php

namespace Validators;

include "../../src/Validators/UsernameValidator.php";
include "../../src/Validators/PasswordValidator.php";
include "../../src/Validators/EmailValidator.php";

use Validators\Result;

class UserDataValidator
{
    public static function validate(string $username, string $email, string $password): Result
    {
        $errors = [];

        // Validate username
        $usernameResult = UsernameValidator::validate($username);
        if (!$usernameResult->isSuccess) {
            $errors = array_merge($errors, $usernameResult->errors);
        }

        // Validate email
        $emailResult = EmailValidator::validate($email);
        if (!$emailResult->isSuccess) {
            $errors = array_merge($errors, $emailResult->errors);
        }

        // Validate password
        $passwordResult = PasswordValidator::validate($password);
        if (!$passwordResult->isSuccess) {
            $errors = array_merge($errors, $passwordResult->errors);
        }

        // Return the combined result object
        if (empty($errors)) {
            return new Result(true);
        } else {
            return new Result(false, $errors);
        }
    }
}