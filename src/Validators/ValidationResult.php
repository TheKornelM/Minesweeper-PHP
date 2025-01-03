<?php

namespace Validators;

class ValidationResult {
    public $isValid;
    public $errors;

    public function __construct($isValid, $errors = []) {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }
}