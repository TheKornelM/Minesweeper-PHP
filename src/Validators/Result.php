<?php

namespace Validators;

/**
 * Class Result
 *
 * Represents the result of a process.
 */
class Result {
    /**
     * @var bool $isSuccess Indicates whether the process was successful.
     */
    public $isSuccess;

    /**
     * @var array $errors An array of error messages, if any.
     */
    public $errors;

    /**
     * ValidationResult constructor.
     *
     * @param bool $isSuccess Indicates whether the process was successful.
     * @param array $errors An optional array of error messages. Defaults to an empty array.
     */
    public function __construct(bool $isSuccess, array $errors = []) {
        $this->isSuccess = $isSuccess;
        $this->errors = $errors;
    }
}