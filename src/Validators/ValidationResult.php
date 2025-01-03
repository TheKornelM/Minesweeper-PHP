<?php

namespace Validators;

/**
 * Class ValidationResult
 *
 * Represents the result of a validation process.
 */
class ValidationResult {
    /**
     * @var bool $isValid Indicates whether the validation was successful.
     */
    public $isValid;

    /**
     * @var array $errors An array of error messages, if any.
     */
    public $errors;

    /**
     * ValidationResult constructor.
     *
     * @param bool $isValid Indicates whether the validation was successful.
     * @param array $errors An optional array of error messages. Defaults to an empty array.
     */
    public function __construct(bool $isValid, array $errors = []) {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }
}